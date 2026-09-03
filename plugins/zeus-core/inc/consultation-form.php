<?php
/**
 * Request Free Consultation — first-party form handler. No third-party
 * form plugin. See docs/PRIVACY-AND-DATA-RETENTION.md for what's
 * collected and why, and docs/DECISIONS.md for the architecture.
 *
 * Flow: theme renders the form (template-parts/consultation-form.php)
 * pointing at admin-post.php?action=zeus_submit_consultation. This file
 * validates, sanitizes, stores a private zeus_lead post, notifies the
 * site owner (locally: a log file; production: wp_mail — see
 * zeus_notify_new_lead()), and redirects to /thank-you/ on success or
 * back to the form with field-level errors on failure.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZEUS_CONSULTATION_NONCE_ACTION', 'zeus_submit_consultation' );
define( 'ZEUS_LEAD_MAX_UPLOAD_BYTES', 10 * 1024 * 1024 ); // 10MB
define( 'ZEUS_LEAD_ALLOWED_UPLOAD_TYPES', 'jpg|jpeg|png|webp|pdf' );

function zeus_consultation_project_types() {
	return array(
		'kitchen'        => __( 'Kitchen Cabinets', 'zeus-core' ),
		'bathroom'       => __( 'Bathroom Cabinets & Vanities', 'zeus-core' ),
		'countertops'    => __( 'Countertops Only', 'zeus-core' ),
		'closet'         => __( 'Custom Closet', 'zeus-core' ),
		'laundry-pantry' => __( 'Laundry & Pantry', 'zeus-core' ),
		'home-office'    => __( 'Home Office', 'zeus-core' ),
		'other'          => __( 'Other', 'zeus-core' ),
	);
}

function zeus_handle_consultation_submission() {
	$redirect_back = ! empty( $_POST['zeus_redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['zeus_redirect_to'] ) ) : home_url( '/consultation/' );
	// Only ever redirect back to a same-site URL.
	if ( wp_parse_url( $redirect_back, PHP_URL_HOST ) !== wp_parse_url( home_url(), PHP_URL_HOST ) ) {
		$redirect_back = home_url( '/consultation/' );
	}

	// 1. Nonce / CSRF check — hard failure, nothing is processed or stored.
	$nonce = isset( $_POST['zeus_consultation_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['zeus_consultation_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, ZEUS_CONSULTATION_NONCE_ACTION ) ) {
		zeus_redirect_with_error( $redirect_back, array( '_form' => __( 'Your session expired — please try again.', 'zeus-core' ) ), array() );
	}

	// 2. Honeypot — real users never see or fill this field. Silent drop,
	// no error shown (don't tip off automated submitters), no data stored.
	if ( ! empty( $_POST['zeus_website'] ) ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}

	// 3. Time-trap — bots submit near-instantly.
	$rendered_at = isset( $_POST['zeus_form_ts'] ) ? absint( $_POST['zeus_form_ts'] ) : 0;
	if ( $rendered_at && ( time() - $rendered_at ) < 3 ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}

	// 4. Basic rate limiting per IP (hashed, never stored — only used as
	// a transient cache key that expires on its own).
	$ip_hash    = hash( 'sha256', ( $_SERVER['REMOTE_ADDR'] ?? 'unknown' ) . wp_salt() );
	$rate_key   = 'zeus_lead_rate_' . $ip_hash;
	$rate_count = (int) get_transient( $rate_key );
	if ( $rate_count >= 5 ) {
		zeus_redirect_with_error( $redirect_back, array( '_form' => __( 'Too many requests — please try again in a bit.', 'zeus-core' ) ), array() );
	}

	// 5. Field validation.
	$errors = array();
	$values = array();

	$values['name'] = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	if ( '' === trim( $values['name'] ) ) {
		$errors['name'] = __( 'Name is required.', 'zeus-core' );
	}

	$values['phone'] = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$phone_digits    = preg_replace( '/\D/', '', $values['phone'] );
	if ( strlen( $phone_digits ) < 7 ) {
		$errors['phone'] = __( 'Enter a valid phone number.', 'zeus-core' );
	}

	$values['email'] = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	if ( ! is_email( $values['email'] ) ) {
		$errors['email'] = __( 'Enter a valid email address.', 'zeus-core' );
	}

	$values['zip'] = isset( $_POST['zip'] ) ? sanitize_text_field( wp_unslash( $_POST['zip'] ) ) : '';
	if ( ! preg_match( '/^\d{5}(-\d{4})?$/', $values['zip'] ) ) {
		$errors['zip'] = __( 'Enter a valid 5-digit ZIP code.', 'zeus-core' );
	}

	$values['project_type'] = isset( $_POST['project_type'] ) ? sanitize_text_field( wp_unslash( $_POST['project_type'] ) ) : '';
	if ( '' !== $values['project_type'] && ! array_key_exists( $values['project_type'], zeus_consultation_project_types() ) ) {
                $errors['project_type'] = __( 'Choose a valid project type.', 'zeus-core' );
        }

	$values['description'] = isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '';
	if ( '' === trim( $values['description'] ) ) {
                $errors['description'] = __( 'Please enter a message.', 'zeus-core' );
        } elseif ( strlen( $values['description'] ) > 5000 ) {
		$errors['description'] = __( 'Please shorten your description.', 'zeus-core' );
	}

	// 6. Upload validation (optional field, but if present must be valid).
	$upload_info = null;
	if ( ! empty( $_FILES['upload']['name'] ) ) {
		$upload_info = zeus_validate_lead_upload( $_FILES['upload'] );
		if ( is_wp_error( $upload_info ) ) {
			$errors['upload'] = $upload_info->get_error_message();
			$upload_info       = null;
		}
	}

	if ( $errors ) {
		zeus_redirect_with_error( $redirect_back, $errors, $values );
	}

	// 7. Persist the lead.
	set_transient( $rate_key, $rate_count + 1, HOUR_IN_SECONDS );

	$lead_id = wp_insert_post(
		array(
			'post_type'   => 'zeus_lead',
			'post_title'  => sprintf( '%s — %s', $values['name'], gmdate( 'Y-m-d H:i' ) ),
			'post_status' => 'private',
		)
	);

	if ( is_wp_error( $lead_id ) || ! $lead_id ) {
		zeus_redirect_with_error( $redirect_back, array( '_form' => __( 'Something went wrong submitting your request. Please try again.', 'zeus-core' ) ), $values );
	}

	update_post_meta( $lead_id, 'zeus_lead_name', $values['name'] );
	update_post_meta( $lead_id, 'zeus_lead_phone', $values['phone'] );
	update_post_meta( $lead_id, 'zeus_lead_email', $values['email'] );
	update_post_meta( $lead_id, 'zeus_lead_zip', $values['zip'] );
	update_post_meta( $lead_id, 'zeus_lead_project_type', $values['project_type'] );
	update_post_meta( $lead_id, 'zeus_lead_description', $values['description'] );
	update_post_meta( $lead_id, 'zeus_lead_submitted_at', current_time( 'mysql' ) );

	if ( $upload_info ) {
		zeus_store_lead_upload( $lead_id, $upload_info );
	}

	zeus_notify_new_lead( $lead_id );

	wp_safe_redirect( home_url( '/thank-you/' ) );
	exit;
}
add_action( 'admin_post_zeus_submit_consultation', 'zeus_handle_consultation_submission' );
add_action( 'admin_post_nopriv_zeus_submit_consultation', 'zeus_handle_consultation_submission' );

/**
 * Validates an uploaded file: real MIME/extension check (not just the
 * client-supplied name), size cap, upload-error check. Returns the
 * $_FILES entry on success or a WP_Error with an accessible message.
 */
function zeus_validate_lead_upload( $file ) {
	if ( ! empty( $file['error'] ) && UPLOAD_ERR_OK !== $file['error'] ) {
		if ( UPLOAD_ERR_INI_SIZE === $file['error'] || UPLOAD_ERR_FORM_SIZE === $file['error'] ) {
			return new WP_Error( 'upload_too_large', __( 'That file is too large (10MB max).', 'zeus-core' ) );
		}
		return new WP_Error( 'upload_error', __( 'There was a problem with your upload — please try again.', 'zeus-core' ) );
	}

	if ( $file['size'] > ZEUS_LEAD_MAX_UPLOAD_BYTES ) {
		return new WP_Error( 'upload_too_large', __( 'That file is too large (10MB max).', 'zeus-core' ) );
	}

	$checked = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], null );
	if ( empty( $checked['ext'] ) || empty( $checked['type'] )
		|| ! preg_match( '/^(' . ZEUS_LEAD_ALLOWED_UPLOAD_TYPES . ')$/i', $checked['ext'] )
	) {
		return new WP_Error( 'upload_bad_type', __( 'Please upload a JPG, PNG, WEBP, or PDF file.', 'zeus-core' ) );
	}

	if ( ! is_uploaded_file( $file['tmp_name'] ) ) {
		return new WP_Error( 'upload_error', __( 'There was a problem with your upload — please try again.', 'zeus-core' ) );
	}

	$file['zeus_checked_ext']  = $checked['ext'];
	$file['zeus_checked_mime'] = $checked['type'];
	return $file;
}

/**
 * Moves a validated upload into the private (non-web-servable) leads
 * directory under a random filename — never added to the public Media
 * Library, never linked publicly. See inc/leads.php for the download
 * gate.
 */
function zeus_store_lead_upload( $lead_id, $file ) {
	zeus_core_setup_private_uploads_dir();
	$dir      = zeus_lead_uploads_dir();
	$rand     = wp_generate_password( 32, false, false );
	$filename = $rand . '.' . $file['zeus_checked_ext'];
	$dest     = $dir . '/' . $filename;

	if ( ! @move_uploaded_file( $file['tmp_name'], $dest ) ) { // phpcs:ignore
		return;
	}

	update_post_meta( $lead_id, 'zeus_lead_upload_path', $filename );
	update_post_meta( $lead_id, 'zeus_lead_upload_mime', $file['zeus_checked_mime'] );
	update_post_meta( $lead_id, 'zeus_lead_upload_original_name', sanitize_file_name( $file['name'] ) );
}

/**
 * Notification transport. Local/dev environments log to a file instead
 * of sending real email (WP_DEBUG or ZEUS_LOCAL_MAIL_LOG); production
 * uses wp_mail() as normal, so switching environments is just WordPress
 * mail/SMTP configuration — no code change, no third-party service
 * assumed, no hardcoded credentials.
 */
function zeus_notify_new_lead( $lead_id ) {
        $name         = get_post_meta( $lead_id, 'zeus_lead_name', true );
        $email        = get_post_meta( $lead_id, 'zeus_lead_email', true );
        $phone        = get_post_meta( $lead_id, 'zeus_lead_phone', true );
        $zip          = get_post_meta( $lead_id, 'zeus_lead_zip', true );
        $type         = get_post_meta( $lead_id, 'zeus_lead_project_type', true );
        $description  = get_post_meta( $lead_id, 'zeus_lead_description', true );
        $submitted_at = get_post_meta( $lead_id, 'zeus_lead_submitted_at', true );
        $upload_name  = get_post_meta( $lead_id, 'zeus_lead_upload_original_name', true );

        $project_types = zeus_consultation_project_types();
        $type_label    = isset( $project_types[ $type ] ) ? $project_types[ $type ] : $type;

        $subject = sprintf( '[ZEUS] New consultation request from %s', $name );

        $body = "NEW REQUEST FREE CONSULTATION\n\n"
                . "Name: {$name}\n"
                . "Phone: {$phone}\n"
                . "Email: {$email}\n"
                . "ZIP: {$zip}\n"
                . "Project type: {$type_label}\n"
                . "Submitted: {$submitted_at}\n\n"
                . "PROJECT DETAILS / MESSAGE:\n"
                . ( $description ? $description : '(No message provided)' )
                . "\n";

        if ( $upload_name ) {
                $body .= "\nUploaded file: {$upload_name}\n";
        }

        $headers = array();

        if ( is_email( $email ) ) {
                $headers[] = 'Reply-To: ' . sanitize_text_field( $name ) . ' <' . $email . '>';
        }

        $use_local_log = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'ZEUS_LOCAL_MAIL_LOG' ) && ZEUS_LOCAL_MAIL_LOG );

        if ( $use_local_log ) {
                $log_file = WP_CONTENT_DIR . '/zeus-lead-mail.log';
                $entry    = '[' . current_time( 'mysql' ) . "] Would send:\nSubject: {$subject}\n{$body}\n" . str_repeat( '-', 40 ) . "\n";
                file_put_contents( $log_file, $entry, FILE_APPEND | LOCK_EX ); // phpcs:ignore
                return;
        }

        wp_mail( 'zeus.cabinets@gmail.com', $subject, $body, $headers );
}

/**
 * Stores validation errors + safe (non-file) field values in a short-
 * lived transient and redirects back to the form with a token, so the
 * form can repopulate itself and show accessible, field-associated
 * error messages without ever putting user input in the URL itself.
 */
function zeus_redirect_with_error( $redirect_to, $errors, $values ) {
	$token = wp_generate_password( 20, false );
	set_transient( 'zeus_form_error_' . $token, array( 'errors' => $errors, 'values' => $values ), 5 * MINUTE_IN_SECONDS );
	$url = add_query_arg( 'zeus_form_token', $token, $redirect_to );
	wp_safe_redirect( $url );
	exit;
}

/**
 * Reads and clears (one-time use) any pending error data for the
 * current request, keyed by the ?zeus_form_token= query arg. Called by
 * the theme's form template — this is the plugin/theme boundary: the
 * plugin owns the data, the theme owns how it's displayed.
 */
function zeus_get_form_error_data() {
	if ( empty( $_GET['zeus_form_token'] ) ) {
		return null;
	}
	$token = sanitize_text_field( wp_unslash( $_GET['zeus_form_token'] ) );
	$data  = get_transient( 'zeus_form_error_' . $token );
	if ( false === $data ) {
		return null;
	}
	delete_transient( 'zeus_form_error_' . $token );
	return $data;
}
