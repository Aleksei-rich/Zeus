<?php
/**
 * Custom taxonomies. See docs/CONTENT-MODEL.md for the full rationale.
 *
 * finish              — shared across cabinet_collection entries
 * project_type        — Kitchen, Bathroom, Closet, Laundry & Pantry, Home Office
 * service_area        — Orlando, Windermere, Winter Garden, Horizon West, Clermont, Dr. Phillips
 * cabinetry_style      — mirrors cabinet_collection entries, used for project archive filtering
 * countertop_material — Quartz, Granite, Porcelain, Marble
 *
 * All of these are internal/filtering taxonomies, not separate indexable
 * archive URLs (see docs/SITE-ARCHITECTURE.md URL map) — rewrite is
 * disabled and they are not publicly queryable as standalone archives.
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
				'name'          => __( 'Finishes', 'zeus' ),
				'singular_name' => __( 'Finish', 'zeus' ),
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
				'name'          => __( 'Project Types', 'zeus' ),
				'singular_name' => __( 'Project Type', 'zeus' ),
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
				'name'          => __( 'Service Areas', 'zeus' ),
				'singular_name' => __( 'Service Area', 'zeus' ),
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
				'name'          => __( 'Cabinetry Styles', 'zeus' ),
				'singular_name' => __( 'Cabinetry Style', 'zeus' ),
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
				'name'          => __( 'Countertop Materials', 'zeus' ),
				'singular_name' => __( 'Countertop Material', 'zeus' ),
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

/**
 * Seed the fixed taxonomy terms this project needs. Idempotent — safe to
 * run on every load; term_exists() short-circuits once terms exist.
 */
function zeus_seed_taxonomy_terms() {

	$finishes = array(
		'Fawn', 'Gray', 'Midnight', 'White', 'Pearl', 'Slate', // Brooklyn
		'Sand', 'Kodiak', 'Moss', // Shaker (White/Fawn/Gray overlap intentionally reused)
		'Oak', 'Walnut', // Oslo
	);
	foreach ( array_unique( $finishes ) as $finish ) {
		if ( ! term_exists( $finish, 'finish' ) ) {
			wp_insert_term( $finish, 'finish' );
		}
	}

	$project_types = array( 'Kitchen', 'Bathroom', 'Closet', 'Laundry & Pantry', 'Home Office' );
	foreach ( $project_types as $type ) {
		if ( ! term_exists( $type, 'project_type' ) ) {
			wp_insert_term( $type, 'project_type' );
		}
	}

	$service_areas = array( 'Orlando', 'Windermere', 'Winter Garden', 'Horizon West', 'Clermont', 'Dr. Phillips' );
	foreach ( $service_areas as $area ) {
		if ( ! term_exists( $area, 'service_area' ) ) {
			wp_insert_term( $area, 'service_area' );
		}
	}

	$countertop_materials = array( 'Quartz', 'Granite', 'Porcelain', 'Marble' );
	foreach ( $countertop_materials as $material ) {
		if ( ! term_exists( $material, 'countertop_material' ) ) {
			wp_insert_term( $material, 'countertop_material' );
		}
	}

	// cabinetry_style terms are created alongside their cabinet_collection
	// posts in inc/post-types.php's seed routine, so the slugs match 1:1.
}
add_action( 'init', 'zeus_seed_taxonomy_terms', 20 );
