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
 * Admin-only assets: media-library-backed image/gallery pickers used by
 * the meta boxes in inc/meta-fields.php.
 */
function zeus_admin_enqueue_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script(
		'zeus-admin-media',
		ZEUS_THEME_URI . '/assets/js/admin-media-fields.js',
		array( 'jquery' ),
		zeus_asset_version( '/assets/js/admin-media-fields.js' ),
		true
	);
}
add_action( 'admin_enqueue_scripts', 'zeus_admin_enqueue_assets' );
