<?php
/**
 * Custom post types: cabinet_collection, project.
 * See docs/CONTENT-MODEL.md for the full rationale and field list.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_register_post_types() {

	register_post_type(
		'cabinet_collection',
		array(
			'labels'       => array(
				'name'          => __( 'Cabinet Collections', 'zeus' ),
				'singular_name' => __( 'Cabinet Collection', 'zeus' ),
				'add_new_item'  => __( 'Add New Collection', 'zeus' ),
				'edit_item'     => __( 'Edit Collection', 'zeus' ),
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
				'name'          => __( 'Projects', 'zeus' ),
				'singular_name' => __( 'Project', 'zeus' ),
				'add_new_item'  => __( 'Add New Project', 'zeus' ),
				'edit_item'     => __( 'Edit Project', 'zeus' ),
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

/**
 * Seed the four real ZEUS cabinet collections as structured content.
 * This is real product-line content the owner's brief specifies
 * (Brooklyn/Shaker/Oslo/Euro-Flat Panel + their finishes) — not
 * fabricated marketing content. No Portfolio projects are seeded here:
 * per explicit instruction, no fake/placeholder projects are created.
 */
function zeus_seed_cabinet_collections() {
	if ( get_option( 'zeus_collections_seeded' ) ) {
		return;
	}

	$collections = array(
		'brooklyn'        => array(
			'title'         => 'Brooklyn',
			'excerpt'       => 'A full-overlay, transitional cabinet collection with a clean, contemporary profile.',
			'content'       => "<p>The Brooklyn collection is ZEUS's full-overlay, transitional cabinet line — a clean, minimal door profile suited to both modern and classic kitchen and bathroom designs.</p><p><em>[Development placeholder: expand with real construction detail, material specification, and photography once available.]</em></p>",
			'profile_type'  => 'Full-overlay transitional',
			'finishes'      => array( 'Fawn', 'Gray', 'Midnight', 'White', 'Pearl', 'Slate' ),
			'menu_order'    => 1,
		),
		'shaker'          => array(
			'title'         => 'Shaker',
			'excerpt'       => 'ZEUS\'s traditional Shaker-style cabinet collection, with a classic five-piece recessed-panel door.',
			'content'       => "<p>The Shaker collection is ZEUS's traditional Shaker cabinet line — a classic five-piece recessed-panel door, distinct from the narrower-rail \"Slim Shaker\" profile used on the Oslo collection.</p><p><em>[Development placeholder: expand with real construction detail, material specification, and photography once available.]</em></p>",
			'profile_type'  => 'Traditional Shaker (five-piece recessed panel)',
			'finishes'      => array( 'White', 'Sand', 'Kodiak', 'Moss' ),
			'menu_order'    => 2,
		),
		'oslo'            => array(
			'title'         => 'Oslo',
			'excerpt'       => 'The Oslo Slim Shaker collection — a narrower-rail Shaker profile, including the Classic Walnut finish.',
			'content'       => "<p>The Oslo collection is ZEUS's Slim Shaker cabinet line — a narrower-rail take on the Shaker door profile, distinct from the traditional Shaker collection. The Walnut finish — <strong>OSLO Classic Walnut Slim Shaker Kitchen Cabinets</strong> — pairs the Slim Shaker profile with a natural walnut finish.</p><p><em>[Development placeholder: expand with real construction detail, material specification, and photography once available.]</em></p>",
			'profile_type'  => 'Slim Shaker (narrow-rail)',
			'finishes'      => array( 'White', 'Oak', 'Walnut' ),
			'menu_order'    => 3,
		),
		'euro-flat-panel' => array(
			'title'         => 'Euro / Flat Panel',
			'excerpt'       => 'ZEUS\'s European-style flat-panel cabinet collection, for a streamlined, handle-integrated look.',
			'content'       => "<p>The Euro / Flat Panel collection is ZEUS's European-style flat-panel cabinet line, built for a streamlined, minimal-hardware look.</p><p><em>[Development placeholder: expand with real construction detail, finish options, material specification, and photography once available.]</em></p>",
			'profile_type'  => 'European flat panel',
			'finishes'      => array(),
			'menu_order'    => 4,
		),
	);

	foreach ( $collections as $slug => $data ) {
		$existing = get_page_by_path( $slug, OBJECT, 'cabinet_collection' );
		if ( $existing ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'cabinet_collection',
				'post_title'   => $data['title'],
				'post_name'    => $slug,
				'post_excerpt' => $data['excerpt'],
				'post_content' => $data['content'],
				'post_status'  => 'publish',
				'menu_order'   => $data['menu_order'],
			)
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		update_post_meta( $post_id, 'zeus_profile_type', $data['profile_type'] );

		if ( ! empty( $data['finishes'] ) ) {
			wp_set_object_terms( $post_id, $data['finishes'], 'finish' );
		}

		// Mirror this collection into the cabinetry_style taxonomy so
		// Portfolio projects can be filtered/related by style later.
		if ( ! term_exists( $data['title'], 'cabinetry_style' ) ) {
			wp_insert_term( $data['title'], 'cabinetry_style', array( 'slug' => $slug ) );
		}
	}

	update_option( 'zeus_collections_seeded', 1 );
}
add_action( 'init', 'zeus_seed_cabinet_collections', 30 );
