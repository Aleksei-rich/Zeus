<?php
/**
 * Custom post types: cabinet_collection, project.
 *
 * Moved from the theme to this first-party plugin so the content model
 * survives a theme change (see docs/DECISIONS.md, "Theme/plugin
 * separation"). Registration args are UNCHANGED from the original
 * theme-owned version — same slugs, same rewrite rules, same supports —
 * so existing URLs and DB content remain fully compatible.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_register_post_types() {

	register_post_type(
		'cabinet_collection',
		array(
			'labels'       => array(
				'name'          => __( 'Cabinet Collections', 'zeus-core' ),
				'singular_name' => __( 'Cabinet Collection', 'zeus-core' ),
				'add_new_item'  => __( 'Add New Collection', 'zeus-core' ),
				'edit_item'     => __( 'Edit Collection', 'zeus-core' ),
			),
			'public'       => true,
			'has_archive'  => 'cabinet-styles',
			'rewrite'      => array( 'slug' => 'cabinet-styles', 'with_front' => false ),
			'menu_icon'    => 'dashicons-layout',
			'menu_position' => 20,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes' ),
			'show_in_rest' => true,
			'template'     => array(),
		)
	);

	register_post_type(
		'project',
		array(
			'labels'       => array(
				'name'          => __( 'Projects', 'zeus-core' ),
				'singular_name' => __( 'Project', 'zeus-core' ),
				'add_new_item'  => __( 'Add New Project', 'zeus-core' ),
				'edit_item'     => __( 'Edit Project', 'zeus-core' ),
			),
			'public'       => true,
			'has_archive'  => 'portfolio',
			'rewrite'      => array( 'slug' => 'portfolio', 'with_front' => false ),
			'menu_icon'    => 'dashicons-portfolio',
			'menu_position' => 21,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'zeus_register_post_types', 5 );
