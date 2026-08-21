<?php
/**
 * ZEUS theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZEUS_THEME_VERSION', '0.1.0' );
define( 'ZEUS_THEME_DIR', get_template_directory() );
define( 'ZEUS_THEME_URI', get_template_directory_uri() );

$zeus_includes = array(
	'inc/setup.php',
	'inc/enqueue.php',
	'inc/taxonomies.php',
	'inc/post-types.php',
	'inc/seed-pages.php',
	'inc/meta-fields.php',
	'inc/template-tags.php',
	'inc/breadcrumbs.php',
	'inc/seo.php',
	'inc/patterns.php',
);

foreach ( $zeus_includes as $zeus_file ) {
	$zeus_path = ZEUS_THEME_DIR . '/' . $zeus_file;
	if ( file_exists( $zeus_path ) ) {
		require_once $zeus_path;
	}
}
