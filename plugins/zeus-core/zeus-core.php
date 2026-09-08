<?php
/**
 * Plugin Name: ZEUS Core
 * Description: First-party site plugin for ZEUS Cabinets & Countertops. Owns content-model registration (CPTs, taxonomies, fields), editorial admin UI, lead capture, and the Request Free Consultation form handler — independent of the active theme. See docs/CONTENT-MODEL.md and docs/DECISIONS.md.
 * Version: 0.1.2
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * Author: ZEUS Cabinets & Countertops
 * Text Domain: zeus-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZEUS_CORE_VERSION', '0.1.2' );
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
 * Hostinger's browser gate protects admin-post.php and returns an HTML
 * JavaScript challenge to XHR requests that do not yet carry hc_js_gate.
 * An XHR cannot execute that returned challenge, so prime the same first-
 * party cookie on pages where the reliable consultation script is loaded.
 * This lets the legitimate form POST reach WordPress on the first attempt.
 */
function zeus_prime_host_browser_gate_cookie() {
	if ( ! wp_script_is( 'zeus-consultation-reliability', 'enqueued' ) ) {
		return;
	}

	wp_add_inline_script(
		'zeus-consultation-reliability',
		"document.cookie='hc_js_gate=1;path=/;SameSite=Lax;Max-Age=3600';",
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'zeus_prime_host_browser_gate_cookie', 30 );

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
