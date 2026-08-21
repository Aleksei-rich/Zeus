<?php
/**
 * Custom taxonomies. Moved from the theme — see inc/post-types.php header.
 * Registration args UNCHANGED from the original theme-owned version.
 * Term seeding lives in inc/seeding.php now (explicit trigger only, not
 * hooked to init — see the seeding-safety notes there).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_register_taxonomies() {

	register_taxonomy(
		'finish',
		array( 'cabinet_collection', 'project' ),
		array(
			'labels'            => array(
				'name'          => __( 'Finishes', 'zeus-core' ),
				'singular_name' => __( 'Finish', 'zeus-core' ),
			),
			'hierarchical'      => false,
			'public'            => false,
			'publicly_queryable' => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => false,
		)
	);

	register_taxonomy(
		'project_type',
		array( 'project' ),
		array(
			'labels'            => array(
				'name'          => __( 'Project Types', 'zeus-core' ),
				'singular_name' => __( 'Project Type', 'zeus-core' ),
			),
			'hierarchical'      => true,
			'public'            => false,
			'publicly_queryable' => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => false,
		)
	);

	register_taxonomy(
		'service_area',
		array( 'project' ),
		array(
			'labels'            => array(
				'name'          => __( 'Service Areas', 'zeus-core' ),
				'singular_name' => __( 'Service Area', 'zeus-core' ),
			),
			'hierarchical'      => false,
			'public'            => false,
			'publicly_queryable' => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => false,
		)
	);

	register_taxonomy(
		'cabinetry_style',
		array( 'project' ),
		array(
			'labels'            => array(
				'name'          => __( 'Cabinetry Styles', 'zeus-core' ),
				'singular_name' => __( 'Cabinetry Style', 'zeus-core' ),
			),
			'hierarchical'      => true,
			'public'            => false,
			'publicly_queryable' => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => false,
		)
	);

	register_taxonomy(
		'countertop_material',
		array( 'project' ),
		array(
			'labels'            => array(
				'name'          => __( 'Countertop Materials', 'zeus-core' ),
				'singular_name' => __( 'Countertop Material', 'zeus-core' ),
			),
			'hierarchical'      => true,
			'public'            => false,
			'publicly_queryable' => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'zeus_register_taxonomies', 5 );
