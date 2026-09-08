<?php
/**
 * Reliability layer for Request Free Consultation.
 *
 * Loaded after consultation-multiupload.php. Keeps the same validation,
 * private storage and admin UI, but removes slow email delivery from the
 * customer request/response path and supports JSON responses for the
 * progressive-enhancement XHR form.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ZEUS_LEAD_MAX_MAIL_ATTACHMENT_BYTES' ) ) {
	define( 'ZEUS_LEAD_MAX_MAIL_ATTACHMENT_BYTES', 8 * 1024 * 1024 ); // 8MB raw; larger sets stay in private lead storage.
}

/**
 * Load progressive-enhancement form UX after the theme's existing main.js.
 * The existing script remains a no-JS-compatible first validation layer;
 * this file adds XHR progress, timeout recovery and in-form network errors.
 */
function zeus_enqueue_consultation_reliability_script() {
	$path = ZEUS_CORE_DIR . 'assets/js/consultation-reliability.js';
	$url  = ZEUS_CORE_URL . 'assets/js/consultation-reliability.js';
	$ver  = file_exists( $path ) ? (string) filemtime( $path ) : ZEUS_CORE_VERSION;

	wp_enqueue_script(
		'zeus-consultation-reliability',
		$url,
		array( 'zeus-main' ),
		$ver,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
}
add_action( 'wp_enqueue_scripts', 'zeus_enqueue_consultation_reliability_script', 20 );

remove_action( 'admin_post_zeus_submit_consultation', 'zeus_handle_consultation_submission_multi' );
remove_action( 'admin_post_nopriv_zeus_submit_consultation', 'zeus_handle_consultation_submission_multi' );
add_action( 'admin_post_zeus_submit_consultation', 'zeus_handle_consultation_submission_reliable' );
add_action( 'admin_post_nopriv_zeus_submit_consultation', 'zeus_handle_consultation_submission_reliable' );

/**
 * XHR is identified by a header as well as POST data. The header survives
 * cases where PHP drops an oversized multipart body before populating $_POST.
 */
function zeus_consultation_wants_json() {
	if ( isset( $_SERVER['HTTP_X_ZEUS_ASYNC'] ) && '1' === (string) $_SERVER['HTTP_X_ZEUS_ASYNC'] ) {
		return true;
	}

	return isset( $_POST['zeus_ajax'] ) && '1' === (string) $_POST['zeus_ajax'];
}

function zeus_consultation_error_response( $redirect_to, $errors, $values = array(), $status = 422 ) {
	if ( zeus_consultation_wants_json() ) {
		wp_send_json_error(
			array(
				'message' => isset( $errors['_form'] ) ? $errors['_form'] : __( 'Please fix the highlighted fields and try again.', 'zeus-core' ),
				'errors'  => $errors,
			),
			$status
		);
	}

	zeus_redirect_with_error( $redirect_to, $errors, $values );
}

function zeus_consultation_success_response( $lead_id ) {
	$redirect = home_url( '/thank-you/' );

	if ( zeus_consultation_wants_json() ) {
		wp_send_json_success(
			array(
				'lead_id'  => (int) $lead_id,
				'redirect' => $redirect,
				'message'  => __( 'Your request was received.', 'zeus-core' ),
			),
			200
		);
	}

	wp_safe_redirect( $redirect );
	exit;
}

/**
 * A client-generated request id makes a retry safe when the browser loses
 * the HTTP response after the server already stored the lead.
 */
function zeus_consultation_submission_id() {
	$value = isset( $_POST['zeus_submission_id'] ) ? sanitize_text_field( wp_unslash( $_POST['zeus_submission_id'] ) ) : '';
	$value = preg_replace( '/[^A-Za-z0-9._:-]/', '', $value );
	return substr( (string) $value, 0, 80 );
}

function zeus_find_lead_by_submission_id( $submission_id ) {
	if ( '' === $submission_id ) {
		return 0;
	}

	$ids = get_posts(
		array(
			'post_type'      => 'zeus_lead',
			'post_status'    => 'private',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_key'       => 'zeus_lead_submission_id',
			'meta_value'     => $submission_id,
		)
	);

	return $ids ? (int) $ids[0] : 0;
}

/**
 * Queue notification after the response-critical storage work. wp-cron is
 * spawned non-blocking so slow MIME encoding / host mail transport cannot
 * leave the customer staring at "Sending…" or cause an HTTP/2 error page.
 */
function zeus_queue_consultation_notification( $lead_id ) {
	$lead_id = (int) $lead_id;
	if ( ! $lead_id ) {
		return false;
	}

	update_post_meta( $lead_id, 'zeus_lead_notification_status', 'queued' );

	if ( ! wp_next_scheduled( 'zeus_send_consultation_notification', array( $lead_id ) ) ) {
		$scheduled = wp_schedule_single_event( time() + 1, 'zeus_send_consultation_notification', array( $lead_id ) );
		if ( false === $scheduled || is_wp_error( $scheduled ) ) {
			return zeus_send_consultation_notification( $lead_id );
		}
	}

	if ( function_exists( 'spawn_cron' ) ) {
		spawn_cron( time() );
	}

	return true;
}

add_action( 'zeus_send_consultation_notification', 'zeus_send_consultation_notification', 10, 1 );

/**
 * Send small attachment sets normally. Larger sets are deliberately not
 * attached because MIME/base64 expansion can add ~33% and trigger hosting
 * mail limits/timeouts. All files remain securely available in the private
 * Consultation Request record.
 */
function zeus_send_consultation_notification( $lead_id ) {
	$lead_id      = (int) $lead_id;
	$name         = get_post_meta( $lead_id, 'zeus_lead_name', true );
	$email        = get_post_meta( $lead_id, 'zeus_lead_email', true );
	$phone        = get_post_meta( $lead_id, 'zeus_lead_phone', true );
	$zip          = get_post_meta( $lead_id, 'zeus_lead_zip', true );
	$type         = get_post_meta( $lead_id, 'zeus_lead_project_type', true );
	$description  = get_post_meta( $lead_id, 'zeus_lead_description', true );
	$submitted_at = get_post_meta( $lead_id, 'zeus_lead_submitted_at', true );
	$uploads      = zeus_get_lead_uploads_multi( $lead_id );

	$project_types = zeus_consultation_project_types();
	$type_label    = isset( $project_types[ $type ] ) ? $project_types[ $type ] : $type;
	$subject       = sprintf( '[ZEUS] New consultation request from %s', $name );
	$body          = "NEW REQUEST FREE CONSULTATION\n\n"
		. "Name: {$name}\nPhone: {$phone}\nEmail: {$email}\nZIP: {$zip}\n"
		. "Project type: {$type_label}\nSubmitted: {$submitted_at}\n\n"
		. "PROJECT DETAILS / MESSAGE:\n" . ( $description ? $description : '(No message provided)' ) . "\n";

	$total_bytes = 0;
	if ( $uploads ) {
		$body .= "\nUPLOADED FILES (" . count( $uploads ) . "):\n";
		foreach ( $uploads as $upload ) {
			$body        .= '- ' . ( $upload['original_name'] ?? 'Project file' ) . "\n";
			$total_bytes += isset( $upload['size'] ) ? (int) $upload['size'] : 0;
		}
	}

	$headers = array();
	if ( is_email( $email ) ) {
		$headers[] = 'Reply-To: ' . sanitize_text_field( $name ) . ' <' . $email . '>';
	}

	$admin_url = admin_url( 'post.php?post=' . $lead_id . '&action=edit' );
	$body     .= "\nOpen this Consultation Request in WordPress:\n{$admin_url}\n";

	$use_local_log = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'ZEUS_LOCAL_MAIL_LOG' ) && ZEUS_LOCAL_MAIL_LOG );
	if ( $use_local_log ) {
		$entry = '[' . current_time( 'mysql' ) . "] Would send:\nSubject: {$subject}\n{$body}\n" . str_repeat( '-', 40 ) . "\n";
		file_put_contents( WP_CONTENT_DIR . '/zeus-lead-mail.log', $entry, FILE_APPEND | LOCK_EX );
		update_post_meta( $lead_id, 'zeus_lead_notification_status', 'logged' );
		return true;
	}

	$prepared = array( 'paths' => array(), 'dir' => '' );
	$attach   = $uploads && $total_bytes > 0 && $total_bytes <= ZEUS_LEAD_MAX_MAIL_ATTACHMENT_BYTES;

	if ( $attach ) {
		$prepared = zeus_prepare_lead_mail_attachments_multi( $uploads );
	} elseif ( $uploads ) {
		$body .= "\nFiles were saved securely with the request but were not attached to email because the combined size is large. Download them from WordPress using the link above.\n";
	}

	$sent     = wp_mail( 'zeus.cabinets@gmail.com', $subject, $body, $headers, $prepared['paths'] );
	$attached = count( $prepared['paths'] );
	zeus_cleanup_lead_mail_attachments_multi( $prepared );

	update_post_meta( $lead_id, 'zeus_lead_notification_attachment_count', $attached );
	update_post_meta( $lead_id, 'zeus_lead_notification_status', $sent ? ( $attached ? 'sent' : 'sent-without-attachments' ) : 'failed' );

	if ( ! $sent && $attached ) {
		$fallback = $body . "\nAttachment delivery failed. The files remain available in the WordPress Consultation Request.\n";
		$sent     = wp_mail( 'zeus.cabinets@gmail.com', $subject, $fallback, $headers );
		update_post_meta( $lead_id, 'zeus_lead_notification_status', $sent ? 'sent-without-attachments' : 'failed' );
	}

	return $sent;
}

function zeus_handle_consultation_submission_reliable() {
	$redirect_back = home_url( '/consultation/' );

	if ( zeus_consultation_request_exceeds_post_max_size() ) {
		zeus_consultation_error_response(
			$redirect_back,
			array( 'uploads' => __( 'Your selected files are too large to send together. Please choose up to 5 files totaling no more than 15MB.', 'zeus-core' ) ),
			array(),
			413
		);
	}

	if ( ! empty( $_POST['zeus_redirect_to'] ) ) {
		$candidate = esc_url_raw( wp_unslash( $_POST['zeus_redirect_to'] ) );
		if ( wp_parse_url( $candidate, PHP_URL_HOST ) === wp_parse_url( home_url(), PHP_URL_HOST ) ) {
			$redirect_back = $candidate;
		}
	}

	$nonce = isset( $_POST['zeus_consultation_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['zeus_consultation_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, ZEUS_CONSULTATION_NONCE_ACTION ) ) {
		zeus_consultation_error_response( $redirect_back, array( '_form' => __( 'Your session expired. Refresh the page and try again.', 'zeus-core' ) ), array(), 403 );
	}

	if ( ! empty( $_POST['zeus_website'] ) ) {
		if ( zeus_consultation_wants_json() ) {
			wp_send_json_success( array( 'redirect' => home_url( '/' ) ), 200 );
		}
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}

	$rendered_at = isset( $_POST['zeus_form_ts'] ) ? absint( $_POST['zeus_form_ts'] ) : 0;
	if ( $rendered_at && ( time() - $rendered_at ) < 3 ) {
		if ( zeus_consultation_wants_json() ) {
			wp_send_json_success( array( 'redirect' => home_url( '/' ) ), 200 );
		}
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}

	$submission_id = zeus_consultation_submission_id();
	$existing_id   = zeus_find_lead_by_submission_id( $submission_id );
	if ( $existing_id ) {
		zeus_consultation_success_response( $existing_id );
	}

	$ip_hash    = hash( 'sha256', ( $_SERVER['REMOTE_ADDR'] ?? 'unknown' ) . wp_salt() );
	$rate_key   = 'zeus_lead_rate_' . $ip_hash;
	$rate_count = (int) get_transient( $rate_key );
	if ( $rate_count >= 5 ) {
		zeus_consultation_error_response( $redirect_back, array( '_form' => __( 'Too many requests were sent from this connection. Please wait a little and try again.', 'zeus-core' ) ), array(), 429 );
	}

	$errors = array();
	$values = array();

	$values['name'] = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	if ( '' === trim( $values['name'] ) ) {
		$errors['name'] = __( 'Name is required.', 'zeus-core' );
	}

	$values['phone'] = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	if ( strlen( preg_replace( '/\D/', '', $values['phone'] ) ) < 7 ) {
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
		$errors['description'] = __( 'Please enter a project description.', 'zeus-core' );
	} elseif ( strlen( $values['description'] ) > 5000 ) {
		$errors['description'] = __( 'Please shorten your project description.', 'zeus-core' );
	}

	$uploads = zeus_collect_consultation_uploads();
	if ( count( $uploads ) > ZEUS_LEAD_MAX_UPLOAD_FILES ) {
		$errors['uploads'] = sprintf(
			__( 'You selected %d files. The maximum is 5. Please remove the extra files and try again.', 'zeus-core' ),
			count( $uploads )
		);
	}

	$validated_uploads = array();
	$total_bytes       = 0;
	if ( empty( $errors['uploads'] ) ) {
		foreach ( $uploads as $file ) {
			$total_bytes += isset( $file['size'] ) ? (int) $file['size'] : 0;

			if ( $total_bytes > ZEUS_LEAD_MAX_TOTAL_UPLOAD_BYTES ) {
				$errors['uploads'] = __( 'Your selected files are over the 15MB total limit. Please remove a file or choose smaller photos.', 'zeus-core' );
				break;
			}

			$validated = zeus_validate_lead_upload_multi( $file );
			if ( is_wp_error( $validated ) ) {
				$errors['uploads'] = $validated->get_error_message();
				break;
			}
			$validated_uploads[] = $validated;
		}
	}

	if ( $errors ) {
		zeus_consultation_error_response( $redirect_back, $errors, $values );
	}

	set_transient( $rate_key, $rate_count + 1, HOUR_IN_SECONDS );

	$lead_id = wp_insert_post(
		array(
			'post_type'   => 'zeus_lead',
			'post_title'  => sprintf( '%s — %s', $values['name'], gmdate( 'Y-m-d H:i' ) ),
			'post_status' => 'private',
		)
	);

	if ( is_wp_error( $lead_id ) || ! $lead_id ) {
		zeus_consultation_error_response( $redirect_back, array( '_form' => __( 'We could not save your request. Please try again.', 'zeus-core' ) ), $values, 500 );
	}

	update_post_meta( $lead_id, 'zeus_lead_name', $values['name'] );
	update_post_meta( $lead_id, 'zeus_lead_phone', $values['phone'] );
	update_post_meta( $lead_id, 'zeus_lead_email', $values['email'] );
	update_post_meta( $lead_id, 'zeus_lead_zip', $values['zip'] );
	update_post_meta( $lead_id, 'zeus_lead_project_type', $values['project_type'] );
	update_post_meta( $lead_id, 'zeus_lead_description', $values['description'] );
	update_post_meta( $lead_id, 'zeus_lead_submitted_at', current_time( 'mysql' ) );
	if ( '' !== $submission_id ) {
		update_post_meta( $lead_id, 'zeus_lead_submission_id', $submission_id );
	}

	if ( $validated_uploads ) {
		$stored = zeus_store_lead_uploads_multi( $lead_id, $validated_uploads );
		if ( is_wp_error( $stored ) ) {
			wp_delete_post( $lead_id, true );
			zeus_consultation_error_response( $redirect_back, array( 'uploads' => $stored->get_error_message() ), $values, 500 );
		}
	}

	zeus_queue_consultation_notification( $lead_id );
	zeus_consultation_success_response( $lead_id );
}
