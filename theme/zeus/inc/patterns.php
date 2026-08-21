<?php
/**
 * Block pattern category for this theme. Individual patterns are
 * auto-registered by WordPress from the /patterns directory (WP 6.0+),
 * each declared via a file header comment — used within block-editor
 * page content (About, Contact, future editorial pages), not for the
 * fully custom-templated homepage.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_register_pattern_category() {
	register_block_pattern_category(
		'zeus',
		array( 'label' => __( 'ZEUS', 'zeus' ) )
	);
}
add_action( 'init', 'zeus_register_pattern_category' );
