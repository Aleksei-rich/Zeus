<?php
/**
 * Asset loading. Minimal footprint: one stylesheet, one small script,
 * no jQuery dependency for frontend code.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_enqueue_assets() {
	wp_enqueue_style(
		'zeus-style',
		ZEUS_THEME_URI . '/assets/css/style.css',
		array(),
		zeus_asset_version( '/assets/css/style.css' )
	);

	wp_enqueue_script(
		'zeus-main',
		ZEUS_THEME_URI . '/assets/js/main.js',
		array(),
		zeus_asset_version( '/assets/js/main.js' ),
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
}
add_action( 'wp_enqueue_scripts', 'zeus_enqueue_assets' );

/**
 * Cache-bust by file mtime in development; falls back to theme version.
 */
function zeus_asset_version( $relative_path ) {
	$file = ZEUS_THEME_DIR . $relative_path;
	return file_exists( $file ) ? (string) filemtime( $file ) : ZEUS_THEME_VERSION;
}

/**
 * Admin-only media pickers moved to the zeus-core plugin
 * (inc/admin-media.php) — they're tied to plugin-owned meta boxes, not
 * theme presentation. See docs/DECISIONS.md, "Theme/plugin separation."
 */

/**
 * Performance: drop WP core's emoji-detection script/style. It exists to
 * polyfill emoji rendering in very old browsers; every browser this site
 * needs to support renders emoji natively, so this is a pure-cost
 * extra request + inline script for no benefit.
 */
function zeus_disable_emoji_scripts() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'zeus_disable_emoji_scripts' );
