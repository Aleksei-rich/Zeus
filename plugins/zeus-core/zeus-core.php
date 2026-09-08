<?php
/**
 * Plugin Name: ZEUS Core
 * Description: First-party site plugin for ZEUS Cabinets & Countertops. Owns content-model registration (CPTs, taxonomies, fields), editorial admin UI, lead capture, and the Request Free Consultation form handler — independent of the active theme. See docs/CONTENT-MODEL.md and docs/DECISIONS.md.
 * Version: 0.1.3
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * Author: ZEUS Cabinets & Countertops
 * Text Domain: zeus-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZEUS_CORE_VERSION', '0.1.3' );
define( 'ZEUS_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'ZEUS_CORE_URL', plugin_dir_url( __FILE__ ) );
define( 'ZEUS_CORE_FILE', __FILE__ );

$zeus_core_includes = array(
	'inc/post-types.php',
	'inc/taxonomies.php',
	'inc/meta-fields.php',
	'inc/admin-media.php',
	'inc/seed-registry.php',
	'inc/seeding.php',
	'inc/admin-tools-page.php',
	'inc/leads.php',
	'inc/consultation-form.php',
	'inc/consultation-multiupload.php',
	'inc/consultation-reliability.php',
	'inc/mail-security.php',
	'inc/google-reviews.php',
);

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	$zeus_core_includes[] = 'inc/cli.php';
}

foreach ( $zeus_core_includes as $zeus_core_file ) {
	$zeus_core_path = ZEUS_CORE_DIR . $zeus_core_file;
	if ( file_exists( $zeus_core_path ) ) {
		require_once $zeus_core_path;
	}
}

/**
 * Public same-origin POST endpoint for the consultation XHR.
 *
 * The hosting browser gate protects /wp-admin/admin-post.php and can return
 * an HTML JavaScript challenge instead of the JSON response expected by the
 * form. The public consultation URL does not need the admin endpoint at all,
 * so handle the exact POST query here before template rendering.
 */
function zeus_handle_public_consultation_endpoint() {
	if ( 'POST' !== strtoupper( (string) ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) ) {
		return;
	}

	$submit = isset( $_GET['zeus_consultation_submit'] ) ? sanitize_text_field( wp_unslash( $_GET['zeus_consultation_submit'] ) ) : '';
	if ( '1' !== $submit ) {
		return;
	}

	if ( ! function_exists( 'zeus_handle_consultation_submission_reliable' ) ) {
		wp_send_json_error(
			array( 'message' => __( 'The consultation form is temporarily unavailable. Please try again.', 'zeus-core' ) ),
			503
		);
	}

	nocache_headers();
	zeus_handle_consultation_submission_reliable();
	exit;
}
add_action( 'template_redirect', 'zeus_handle_public_consultation_endpoint', 0 );

/**
 * Prime the host browser-gate cookie and point the progressive-enhancement
 * form at the public endpoint above. The form markup itself remains backward
 * compatible; without JavaScript it still has its original admin-post action.
 */
function zeus_prime_consultation_frontend_endpoint() {
	if ( ! wp_script_is( 'zeus-consultation-reliability', 'enqueued' ) ) {
		return;
	}

	$endpoint = add_query_arg( 'zeus_consultation_submit', '1', home_url( '/consultation/' ) );
	$script   = "document.cookie='hc_js_gate=1;path=/;SameSite=Lax;Max-Age=3600';"
		. "var zeusForm=document.querySelector('[data-zeus-consultation-form]');"
		. 'if(zeusForm){zeusForm.action=' . wp_json_encode( $endpoint ) . ';}';

	wp_add_inline_script(
		'zeus-consultation-reliability',
		$script,
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'zeus_prime_consultation_frontend_endpoint', 30 );

/**
 * Activation only ever provisions infrastructure (a protected upload
 * directory) — it never creates content. Content seeding is always an
 * explicit, separate action (`wp zeus seed` or Tools > ZEUS Setup).
 */
function zeus_core_on_activation() {
	if ( function_exists( 'zeus_core_setup_private_uploads_dir' ) ) {
		zeus_core_setup_private_uploads_dir();
	}
	// `init` has already fired earlier in this same request (activation
	// runs after WP's normal bootstrap), so register directly here too —
	// otherwise the flush below would run before WP knows about our
	// rewrite slugs and the archive/single URLs would 404 until the next
	// unrelated flush.
	if ( function_exists( 'zeus_register_post_types' ) ) {
		zeus_register_post_types();
	}
	if ( function_exists( 'zeus_register_taxonomies' ) ) {
		zeus_register_taxonomies();
	}
	if ( function_exists( 'zeus_register_lead_post_type' ) ) {
		zeus_register_lead_post_type();
	}
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'zeus_core_on_activation' );

function zeus_core_on_deactivation() {
	if ( function_exists( 'zeus_google_reviews_deactivate' ) ) {
		zeus_google_reviews_deactivate();
	}
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'zeus_core_on_deactivation' );
