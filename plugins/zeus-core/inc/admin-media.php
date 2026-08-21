<?php
/**
 * Admin-only media-library-backed pickers for the meta boxes in
 * inc/meta-fields.php. Moved from the theme — this is editorial tooling
 * tied to plugin-owned fields, not presentation.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_core_admin_enqueue_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script(
		'zeus-core-admin-media',
		ZEUS_CORE_URL . 'assets/js/admin-media-fields.js',
		array( 'jquery' ),
		file_exists( ZEUS_CORE_DIR . 'assets/js/admin-media-fields.js' ) ? (string) filemtime( ZEUS_CORE_DIR . 'assets/js/admin-media-fields.js' ) : ZEUS_CORE_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'zeus_core_admin_enqueue_assets' );
