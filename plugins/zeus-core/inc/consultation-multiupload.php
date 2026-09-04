<?php
/**
 * Multi-file layer for Request Free Consultation.
 *
 * Loaded after consultation-form.php. It replaces only the submission
 * handler so the existing validation/error helpers and private lead
 * storage architecture remain intact.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZEUS_LEAD_MAX_UPLOAD_FILES', 5 );
define( 'ZEUS_LEAD_MAX_TOTAL_UPLOAD_BYTES', 15 * 1024 * 1024 ); // 15MB total.

remove_action( 'admin_post_zeus_submit_consultation', 'zeus_handle_consultation_submission' );
remove_action( 'admin_post_nopriv_zeus_submit_consultation', 'zeus_handle_consultation_submission' );
add_action( 'admin_post_zeus_submit_consultation', 'zeus_handle_consultation_submission_multi' );
add_action( 'admin_post_nopriv_zeus_submit_consultation', 'zeus_handle_consultation_submission_multi' );

function zeus_consultation_ini_bytes( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return 0;
	}

	$last = strtolower( substr( $value, -1 ) );
	$size = (float) $value;

	switch ( $last ) {
		case 'g':
			$size *= 1024;
			// Fall through.
		case 'm':
			$size *= 1024;
			// Fall through.
		case 'k':
			$size *= 1024;
			break;
	}

	return (int) $size;
}

function zeus_consultation_request_exceeds_post_max_size() {
	$content_length = isset( $_SERVER['CONTENT_LENGTH'] ) ? (int) $_SERVER['CONTENT_LENGTH'] : 0;
	$post_max        = zeus_consultation_ini_bytes( ini_get( 'post_max_size' ) );

	return $content_length > 0 && $post_max > 0 && $content_length > $post_max;
}

/**
 * Normalize uploads[] to ordinary single-file entries. The old upload
 * field is accepted during cache turnover for backwards compatibility.
 */
function zeus_collect_consultation_uploads() {
	$files = array();

	if ( isset( $_FILES['uploads']['name'] ) && is_array( $_FILES['uploads']['name'] ) ) {
		$count = count( $_FILES['uploads']['name'] );
		for ( $i = 0; $i < $count; $i++ ) {
			$error = isset( $_FILES['uploads']['error'][ $i ] ) ? (int) $_FILES['uploads']['error'][ $i ] : UPLOAD_ERR_NO_FILE;
			$name  = isset( $_FILES['uploads']['name'][ $i ] ) ? (string) $_FILES['uploads']['name'][ $i ] : '';

			if ( UPLOAD_ERR_NO_FILE === $error || '' === $name ) {
				continue;
			}

			$files[] = array(
				'name'     => $name,
				'type'     => isset( $_FILES['uploads']['type'][ $i ] ) ? (string) $_FILES['uploads']['type'][ $i ] : '',
				'tmp_name' => isset( $_FILES['uploads']['tmp_name'][ $i ] ) ? (string) $_FILES['uploads']['tmp_name'][ $i ] : '',
				'error'    => $error,
				'size'     => isset( $_FILES['uploads']['size'][ $i ] ) ? (int) $_FILES['uploads']['size'][ $i ] : 0,
			);
		}
	} elseif ( ! empty( $_FILES['upload']['name'] ) ) {
		$files[] = $_FILES['upload'];
	}

	return $files;
}

function zeus_validate_lead_upload_multi( $file ) {
	if ( ! empty( $file['error'] ) && UPLOAD_ERR_OK !== (int) $file['error'] ) {
		if ( UPLOAD_ERR_INI_SIZE === (int) $file['error'] || UPLOAD_ERR_FORM_SIZE === (int) $file['error'] ) {
			return new WP_Error( 'upload_too_large', __( 'One of the files is too large (10MB max per file).', 'zeus-core' ) );
		}
		return new WP_Error( 'upload_error', __( 'There was a problem with one of the uploads — please try again.', 'zeus-core' ) );
	}

	if ( empty( $file['tmp_name'] ) || ! is_uploaded_file( $file['tmp_name'] ) ) {
		return new WP_Error( 'upload_error', __( 'There was a problem with one of the uploads — please try again.', 'zeus-core' ) );
	}

	if ( (int) $file['size'] > ZEUS_LEAD_MAX_UPLOAD_BYTES ) {
		return new WP_Error( 'upload_too_large', __( 'One of the files is too large (10MB max per file).', 'zeus-core' ) );
	}

	$allowed_mimes = array(
		'jpg|jpeg' => 'image/jpeg',
		'png'      => 'image/png',
		'webp'     => 'image/webp',
		'heic'     => 'image/heic',
		'heif'     => 'image/heif',
		'pdf'      => 'application/pdf',
	);
	$checked = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], $allowed_mimes );

	if ( empty( $checked['ext'] ) || empty( $checked['type'] ) ) {
		return new WP_Error( 'upload_bad_type', __( 'Please upload JPG, PNG, WEBP, HEIC/HEIF, or PDF files.', 'zeus-core' ) );
	}

	$file['zeus_checked_ext']  = $checked['ext'];
	$file['zeus_checked_mime'] = $checked['type'];
	return $file;
}

function zeus_store_lead_uploads_multi( $lead_id, $files ) {
	zeus_core_setup_private_uploads_dir();
	$dir    = zeus_lead_uploads_dir();
	$stored = array();

	foreach ( $files as $file ) {
		$filename = wp_generate_password( 32, false, false ) . '.' . $file['zeus_checked_ext'];
		$dest     = trailingslashit( $dir ) . $filename;

		if ( ! @move_uploaded_file( $file['tmp_name'], $dest ) ) { // phpcs:ignore
			foreach ( $stored as $stored_file ) {
				$rollback = trailingslashit( $dir ) . $stored_file['path'];
				if ( is_file( $rollback ) ) {
					@unlink( $rollback ); // phpcs:ignore
				}
			}
			return new WP_Error( 'upload_store_failed', __( 'We could not save your photos. Please try again.', 'zeus-core' ) );
		}

		$stored[] = array(
			'path'          => $filename,
			'mime'          => $file['zeus_checked_mime'],
			'original_name' => sanitize_file_name( $file['name'] ),
			'size'          => (int) $file['size'],
		);
	}

	update_post_meta( $lead_id, 'zeus_lead_uploads', $stored );

	// Keep legacy first-file meta populated for older admin code/leads.
	if ( ! empty( $stored[0] ) ) {
		update_post_meta( $lead_id, 'zeus_lead_upload_path', $stored[0]['path'] );
		update_post_meta( $lead_id, 'zeus_lead_upload_mime', $stored[0]['mime'] );
		update_post_meta( $lead_id, 'zeus_lead_upload_original_name', $stored[0]['original_name'] );
	}

	return $stored;
}

function zeus_get_lead_uploads_multi( $lead_id ) {
	$uploads = get_post_meta( $lead_id, 'zeus_lead_uploads', true );
	if ( is_array( $uploads ) && $uploads ) {
		return $uploads;
	}

	$path = get_post_meta( $lead_id, 'zeus_lead_upload_path', true );
	if ( ! $path ) {
		return array();
	}

	return array(
		array(
			'path'          => $path,
			'mime'          => get_post_meta( $lead_id, 'zeus_lead_upload_mime', true ),
			'original_name' => get_post_meta( $lead_id, 'zeus_lead_upload_original_name', true ),
			'size'          => 0,
		),
	);
}

function zeus_resolve_lead_upload_path_multi( $relative_path ) {
	$dir       = zeus_lead_uploads_dir();
	$real_dir  = realpath( $dir );
	$real_path = realpath( trailingslashit( $dir ) . ltrim( (string) $relative_path, '/' ) );

	if ( ! $real_path || ! $real_dir || 0 !== strpos( $real_path, $real_dir ) ) {
		return false;
	}
	return $real_path;
}

/**
 * Copy private files to a temporary directory using the customer's
 * original filenames so wp_mail attachments are human-readable.
 */
function zeus_prepare_lead_mail_attachments_multi( $uploads ) {
	$result = array( 'paths' => array(), 'dir' => '' );
	if ( ! $uploads ) {
		return $result;
	}

	$temp_dir = trailingslashit( get_temp_dir() ) . 'zeus-mail-' . wp_generate_password( 12, false, false );
	if ( ! wp_mkdir_p( $temp_dir ) ) {
		return $result;
	}

	$result['dir'] = $temp_dir;
	$used_names    = array();

	foreach ( $uploads as $index => $upload ) {
		$source = zeus_resolve_lead_upload_path_multi( $upload['path'] ?? '' );
		if ( ! $source ) {
			continue;
		}

		$name = sanitize_file_name( $upload['original_name'] ?? '' );
		if ( '' === $name ) {
			$name = 'project-file-' . ( $index + 1 );
		}

		if ( isset( $used_names[ $name ] ) ) {
			$info = pathinfo( $name );
			$name = ( $info['filename'] ?? 'project-file' ) . '-' . ( $index + 1 ) . ( isset( $info['extension'] ) ? '.' . $info['extension'] : '' );
		}
		$used_names[ $name ] = true;

		$target = trailingslashit( $temp_dir ) . $name;
		if ( @copy( $source, $target ) ) { // phpcs:ignore
			$result['paths'][] = $target;
		}
	}

	return $result;
}

function zeus_cleanup_lead_mail_attachments_multi( $prepared ) {
	foreach ( (array) ( $prepared['paths'] ?? array() ) as $path ) {
		if ( is_file( $path ) ) {
			@unlink( $path ); // phpcs:ignore
		}
	}
	if ( ! empty( $prepared['dir'] ) && is_dir( $prepared['dir'] ) ) {
		@rmdir( $prepared['dir'] ); // phpcs:ignore
	}
}

function zeus_notify_new_lead_multi( $lead_id ) {
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

	if ( $uploads ) {
		$body .= "\nUPLOADED FILES (" . count( $uploads ) . "):\n";
		foreach ( $uploads as $upload ) {
			$body .= '- ' . ( $upload['original_name'] ?? 'Project file' ) . "\n";
		}
	}

	$headers = array();
	if ( is_email( $email ) ) {
		$headers[] = 'Reply-To: ' . sanitize_text_field( $name ) . ' <' . $email . '>';
	}

	$use_local_log = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'ZEUS_LOCAL_MAIL_LOG' ) && ZEUS_LOCAL_MAIL_LOG );
	if ( $use_local_log ) {
		$entry = '[' . current_time( 'mysql' ) . "] Would send:\nSubject: {$subject}\n{$body}\n" . str_repeat( '-', 40 ) . "\n";
		file_put_contents( WP_CONTENT_DIR . '/zeus-lead-mail.log', $entry, FILE_APPEND | LOCK_EX ); // phpcs:ignore
		return true;
	}

	$prepared = zeus_prepare_lead_mail_attachments_multi( $uploads );
	$sent     = wp_mail( 'zeus.cabinets@gmail.com', $subject, $body, $headers, $prepared['paths'] );
	$attached = count( $prepared['paths'] );
	zeus_cleanup_lead_mail_attachments_multi( $prepared );

	update_post_meta( $lead_id, 'zeus_lead_notification_status', $sent ? 'sent' : 'failed' );
	update_post_meta( $lead_id, 'zeus_lead_notification_attachment_count', $attached );

	// Do not lose the notification if the host rejects mail attachments.
	if ( ! $sent && $uploads ) {
		$fallback_body = $body . "\nThe files were saved in WordPress Consultation Requests but could not be attached to this email.\n";
		$sent          = wp_mail( 'zeus.cabinets@gmail.com', $subject, $fallback_body, $headers );
		update_post_meta( $lead_id, 'zeus_lead_notification_status', $sent ? 'sent-without-attachments' : 'failed' );
	}

	return $sent;
}

function zeus_handle_consultation_submission_multi() {
	if ( zeus_consultation_request_exceeds_post_max_size() ) {
		zeus_redirect_with_error(
			home_url( '/consultation/' ),
			array( '_form' => __( 'Your photos are too large to send together. Please choose up to 5 files totaling no more than 15MB.', 'zeus-core' ) ),
			array()
		);
	}

	$redirect_back = ! empty( $_POST['zeus_redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['zeus_redirect_to'] ) ) : home_url( '/consultation/' );
	if ( wp_parse_url( $redirect_back, PHP_URL_HOST ) !== wp_parse_url( home_url(), PHP_URL_HOST ) ) {
		$redirect_back = home_url( '/consultation/' );
	}

	$nonce = isset( $_POST['zeus_consultation_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['zeus_consultation_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, ZEUS_CONSULTATION_NONCE_ACTION ) ) {
		zeus_redirect_with_error( $redirect_back, array( '_form' => __( 'Your session expired — please try again.', 'zeus-core' ) ), array() );
	}

	if ( ! empty( $_POST['zeus_website'] ) ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}

	$rendered_at = isset( $_POST['zeus_form_ts'] ) ? absint( $_POST['zeus_form_ts'] ) : 0;
	if ( $rendered_at && ( time() - $rendered_at ) < 3 ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}

	$ip_hash    = hash( 'sha256', ( $_SERVER['REMOTE_ADDR'] ?? 'unknown' ) . wp_salt() );
	$rate_key   = 'zeus_lead_rate_' . $ip_hash;
	$rate_count = (int) get_transient( $rate_key );
	if ( $rate_count >= 5 ) {
		zeus_redirect_with_error( $redirect_back, array( '_form' => __( 'Too many requests — please try again in a bit.', 'zeus-core' ) ), array() );
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
		$errors['description'] = __( 'Please enter a message.', 'zeus-core' );
	} elseif ( strlen( $values['description'] ) > 5000 ) {
		$errors['description'] = __( 'Please shorten your description.', 'zeus-core' );
	}

	$uploads = zeus_collect_consultation_uploads();
	if ( count( $uploads ) > ZEUS_LEAD_MAX_UPLOAD_FILES ) {
		$errors['uploads'] = __( 'Please choose no more than 5 files.', 'zeus-core' );
	}

	$validated_uploads = array();
	$total_bytes       = 0;
	if ( empty( $errors['uploads'] ) ) {
		foreach ( $uploads as $file ) {
			$total_bytes += isset( $file['size'] ) ? (int) $file['size'] : 0;
			if ( $total_bytes > ZEUS_LEAD_MAX_TOTAL_UPLOAD_BYTES ) {
				$errors['uploads'] = __( 'Your selected files are too large together (15MB total max).', 'zeus-core' );
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
		zeus_redirect_with_error( $redirect_back, $errors, $values );
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
		zeus_redirect_with_error( $redirect_back, array( '_form' => __( 'Something went wrong submitting your request. Please try again.', 'zeus-core' ) ), $values );
	}

	update_post_meta( $lead_id, 'zeus_lead_name', $values['name'] );
	update_post_meta( $lead_id, 'zeus_lead_phone', $values['phone'] );
	update_post_meta( $lead_id, 'zeus_lead_email', $values['email'] );
	update_post_meta( $lead_id, 'zeus_lead_zip', $values['zip'] );
	update_post_meta( $lead_id, 'zeus_lead_project_type', $values['project_type'] );
	update_post_meta( $lead_id, 'zeus_lead_description', $values['description'] );
	update_post_meta( $lead_id, 'zeus_lead_submitted_at', current_time( 'mysql' ) );

	if ( $validated_uploads ) {
		$stored = zeus_store_lead_uploads_multi( $lead_id, $validated_uploads );
		if ( is_wp_error( $stored ) ) {
			wp_delete_post( $lead_id, true );
			zeus_redirect_with_error( $redirect_back, array( 'uploads' => $stored->get_error_message() ), $values );
		}
	}

	zeus_notify_new_lead_multi( $lead_id );
	wp_safe_redirect( home_url( '/thank-you/' ) );
	exit;
}

/**
 * Show every upload in the Consultation Requests list without changing
 * the historical single-file storage/download path.
 */
remove_action( 'manage_zeus_lead_posts_custom_column', 'zeus_lead_admin_column_content', 10 );
add_action( 'manage_zeus_lead_posts_custom_column', 'zeus_lead_admin_column_content_multi', 10, 2 );

function zeus_lead_admin_column_content_multi( $column, $post_id ) {
	if ( 'zeus_upload' !== $column ) {
		zeus_lead_admin_column_content( $column, $post_id );
		return;
	}

	$uploads = zeus_get_lead_uploads_multi( $post_id );
	if ( ! $uploads ) {
		echo '&#8212;';
		return;
	}

	$links = array();
	foreach ( $uploads as $index => $upload ) {
		$url = wp_nonce_url(
			admin_url( 'admin-post.php?action=zeus_download_lead_upload_multi&lead_id=' . $post_id . '&file_index=' . $index ),
			'zeus_download_lead_upload_multi_' . $post_id . '_' . $index
		);
		$label   = ! empty( $upload['original_name'] ) ? $upload['original_name'] : sprintf( __( 'File %d', 'zeus-core' ), $index + 1 );
		$links[] = '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
	}

	echo implode( '<br>', $links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function zeus_handle_lead_download_multi() {
	$lead_id = isset( $_GET['lead_id'] ) ? absint( $_GET['lead_id'] ) : 0;
	$index   = isset( $_GET['file_index'] ) ? absint( $_GET['file_index'] ) : 0;

	if ( ! $lead_id || ! current_user_can( 'edit_post', $lead_id ) ) {
		wp_die( esc_html__( 'You do not have permission to access this file.', 'zeus-core' ), 403 );
	}
	check_admin_referer( 'zeus_download_lead_upload_multi_' . $lead_id . '_' . $index );

	$uploads = zeus_get_lead_uploads_multi( $lead_id );
	if ( ! isset( $uploads[ $index ] ) ) {
		wp_die( esc_html__( 'No file found for this request.', 'zeus-core' ), 404 );
	}

	$upload = $uploads[ $index ];
	$path   = zeus_resolve_lead_upload_path_multi( $upload['path'] ?? '' );
	if ( ! $path ) {
		wp_die( esc_html__( 'File not found.', 'zeus-core' ), 404 );
	}

	$filename = sanitize_file_name( $upload['original_name'] ?? 'upload' );
	$mime     = sanitize_mime_type( $upload['mime'] ?? 'application/octet-stream' );

	nocache_headers();
	header( 'Content-Type: ' . ( $mime ? $mime : 'application/octet-stream' ) );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
	header( 'Content-Length: ' . filesize( $path ) );
	readfile( $path ); // phpcs:ignore
	exit;
}
add_action( 'admin_post_zeus_download_lead_upload_multi', 'zeus_handle_lead_download_multi' );
