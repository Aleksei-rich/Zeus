<?php
/**
 * Consultation email delivery policy.
 *
 * Every file accepted by the public consultation form must also be delivered
 * to the ZEUS notification mailbox as an email attachment. The lead's private
 * WordPress storage remains a backup/audit copy, not the normal way to obtain
 * customer files.
 *
 * Delivery strategy:
 * 1. Try one message with the entire accepted upload set (up to 15MB raw).
 * 2. If the mail transport refuses that message, retry as smaller attachment
 *    batches (up to 10MB raw per message).
 * 3. If a multi-file batch still fails, retry each file in that batch as its
 *    own email. We never intentionally replace attachments with a WordPress
 *    download-link-only notification.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Split stored upload metadata into raw-size batches for fallback delivery.
 * A single accepted file can be up to 10MB, so a file is never split.
 */
function zeus_consultation_mail_batches( $uploads, $max_bytes = 10485760 ) {
	$batches = array();
	$current = array();
	$bytes   = 0;

	foreach ( (array) $uploads as $upload ) {
		$size = max( 0, (int) ( $upload['size'] ?? 0 ) );

		if ( $current && $bytes + $size > $max_bytes ) {
			$batches[] = $current;
			$current   = array();
			$bytes     = 0;
		}

		$current[] = $upload;
		$bytes    += $size;
	}

	if ( $current ) {
		$batches[] = $current;
	}

	return $batches;
}

/**
 * Send one message with a specific subset of stored uploads.
 */
function zeus_consultation_send_attachment_message( $to, $subject, $body, $headers, $uploads ) {
	$prepared = zeus_prepare_lead_mail_attachments_multi( $uploads );
	$paths    = (array) ( $prepared['paths'] ?? array() );

	// If an upload exists but could not be prepared, do not pretend the
	// attachment message succeeded.
	if ( $uploads && count( $paths ) !== count( $uploads ) ) {
		zeus_cleanup_lead_mail_attachments_multi( $prepared );
		return false;
	}

	$sent = wp_mail( $to, $subject, $body, $headers, $paths );
	zeus_cleanup_lead_mail_attachments_multi( $prepared );

	return (bool) $sent;
}

/**
 * Replacement notification handler: accepted customer uploads are delivered
 * by email, not intentionally withheld because the combined raw size exceeds
 * 10MB.
 */
function zeus_send_consultation_notification_all_files( $lead_id ) {
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

	$to            = 'zeus.cabinets@gmail.com';
	$use_local_log = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'ZEUS_LOCAL_MAIL_LOG' ) && ZEUS_LOCAL_MAIL_LOG );
	if ( $use_local_log ) {
		$entry = '[' . current_time( 'mysql' ) . "] Would send with " . count( $uploads ) . " attachment(s):\nSubject: {$subject}\n{$body}\n" . str_repeat( '-', 40 ) . "\n";
		file_put_contents( WP_CONTENT_DIR . '/zeus-lead-mail.log', $entry, FILE_APPEND | LOCK_EX ); // phpcs:ignore
		update_post_meta( $lead_id, 'zeus_lead_notification_status', 'logged' );
		update_post_meta( $lead_id, 'zeus_lead_notification_attachment_count', count( $uploads ) );
		return true;
	}

	// No uploads: ordinary notification email.
	if ( ! $uploads ) {
		$sent = wp_mail( $to, $subject, $body, $headers );
		update_post_meta( $lead_id, 'zeus_lead_notification_status', $sent ? 'sent' : 'failed' );
		update_post_meta( $lead_id, 'zeus_lead_notification_attachment_count', 0 );
		return (bool) $sent;
	}

	// First try the preferred experience: one email containing every file.
	if ( zeus_consultation_send_attachment_message( $to, $subject, $body, $headers, $uploads ) ) {
		update_post_meta( $lead_id, 'zeus_lead_notification_status', 'sent' );
		update_post_meta( $lead_id, 'zeus_lead_notification_attachment_count', count( $uploads ) );
		update_post_meta( $lead_id, 'zeus_lead_notification_email_parts', 1 );
		return true;
	}

	// Mail transport rejected the full set. Retry in smaller raw-size batches.
	$batches        = zeus_consultation_mail_batches( $uploads );
	$total_parts    = count( $batches );
	$sent_files     = 0;
	$sent_messages  = 0;
	$failed_uploads = array();

	foreach ( $batches as $index => $batch ) {
		$part       = $index + 1;
		$part_title = sprintf( '%s — files %d/%d', $subject, $part, $total_parts );
		$part_body  = $body . sprintf( "\nATTACHMENT EMAIL %d OF %d\n", $part, $total_parts );

		if ( zeus_consultation_send_attachment_message( $to, $part_title, $part_body, $headers, $batch ) ) {
			$sent_files += count( $batch );
			$sent_messages++;
			continue;
		}

		// Last delivery fallback: one email per file. This keeps the normal
		// workflow in email even if the host rejects a larger MIME message.
		foreach ( $batch as $file_index => $upload ) {
			$file_name    = $upload['original_name'] ?? 'Project file';
			$single_title = sprintf( '%s — attachment %s', $subject, $file_name );
			$single_body  = $body . "\nATTACHED FILE: {$file_name}\n";

			if ( zeus_consultation_send_attachment_message( $to, $single_title, $single_body, $headers, array( $upload ) ) ) {
				$sent_files++;
				$sent_messages++;
			} else {
				$failed_uploads[] = $file_name;
			}
		}
	}

	update_post_meta( $lead_id, 'zeus_lead_notification_attachment_count', $sent_files );
	update_post_meta( $lead_id, 'zeus_lead_notification_email_parts', $sent_messages );

	if ( ! $failed_uploads && $sent_files === count( $uploads ) ) {
		update_post_meta( $lead_id, 'zeus_lead_notification_status', 'sent' );
		return true;
	}

	// Never silently claim success when an attachment could not be emailed.
	update_post_meta( $lead_id, 'zeus_lead_notification_status', 'attachment-delivery-failed' );
	update_post_meta( $lead_id, 'zeus_lead_notification_failed_files', $failed_uploads );

	$alert = "EMAIL ATTACHMENT DELIVERY ERROR\n\nThe consultation request was saved, but these file(s) could not be delivered as email attachments:\n- " . implode( "\n- ", $failed_uploads ) . "\n\nPlease investigate the outgoing mail transport.\n";
	wp_mail( $to, '[ZEUS] Attachment delivery error', $alert );

	return false;
}

// Replace the reliability layer's original mail callback. The original
// function stays available for backwards compatibility, but scheduled
// consultation notifications use the all-files policy above.
remove_action( 'zeus_send_consultation_notification', 'zeus_send_consultation_notification', 10 );
add_action( 'zeus_send_consultation_notification', 'zeus_send_consultation_notification_all_files', 10, 1 );
