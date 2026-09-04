<?php
/**
 * Mail deliverability + HTTPS hardening.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Align the SMTP envelope sender / Return-Path with the ZEUS domain.
 * This improves SPF alignment when the hosting server is authorized for
 * zeuscabinetsflorida.com while keeping the visible From address unchanged.
 */
function zeus_align_mail_envelope_sender( $phpmailer ) {
	$sender = 'wordpress@zeuscabinetsflorida.com';

	if ( method_exists( $phpmailer, 'setFrom' ) && empty( $phpmailer->From ) ) {
		$phpmailer->setFrom( $sender, 'WordPress', false );
	}

	$phpmailer->Sender = $sender;
}
add_action( 'phpmailer_init', 'zeus_align_mail_envelope_sender', 20 );

/**
 * Upgrade any accidental insecure subresource request to HTTPS. This is
 * intentionally narrow: it does not relax any browser security policy.
 */
function zeus_send_https_upgrade_header() {
	if ( ! headers_sent() ) {
		header( 'Content-Security-Policy: upgrade-insecure-requests' );
	}
}
add_action( 'send_headers', 'zeus_send_https_upgrade_header' );
