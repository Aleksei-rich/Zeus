<?php
/**
 * Contextual internal-link clusters for commercial service pages.
 *
 * These links are rendered server-side immediately before the footer so
 * search engines and visitors can move naturally between high-intent service
 * pages and their supporting guides. Keep this file factual and lightweight:
 * it adds navigation only and does not introduce new business claims.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render a compact planning-guides section on selected commercial pages.
 */
function zeus_render_commercial_seo_cluster_links() {
	if ( is_admin() ) {
		return;
	}

	$page_key = '';

	if ( is_page( 'kitchen-cabinets' ) ) {
		$page_key = 'kitchen';
	} elseif ( is_page( 'bathroom-cabinets-vanities' ) ) {
		$page_key = 'bathroom';
	} elseif ( is_page( 'home-office' ) ) {
		$page_key = 'home-office';
	} elseif ( is_page( 'closets' ) ) {
		$page_key = 'closets';
	}

	if ( ! $page_key ) {
		return;
	}

	$clusters = array(
		'kitchen' => array(
			'heading' => __( 'Kitchen Planning Guides', 'zeus' ),
			'intro'   => __( 'Use these practical guides to plan cabinet style, measurements, budget, and installation before your consultation.', 'zeus' ),
			'links'   => array(
				array(
					'label' => __( 'How Much Does a New Kitchen Cost in Orlando in 2026?', 'zeus' ),
					'url'   => home_url( '/new-kitchen-cost-orlando-2026/' ),
				),
				array(
					'label' => __( 'How to Measure Your Kitchen for a Cabinet Estimate', 'zeus' ),
					'url'   => home_url( '/how-to-measure-kitchen-for-cabinet-estimate/' ),
				),
				array(
					'label' => __( 'Shaker vs Slim Shaker Cabinets', 'zeus' ),
					'url'   => home_url( '/shaker-vs-slim-shaker-cabinets/' ),
				),
				array(
					'label' => __( 'How Long Does Cabinet Installation Take?', 'zeus' ),
					'url'   => home_url( '/how-long-does-cabinet-installation-take/' ),
				),
			),
		),
		'bathroom' => array(
			'heading' => __( 'Bathroom Vanity Planning Guides', 'zeus' ),
			'intro'   => __( 'Plan vanity storage, clearances, cabinet style, and countertop material before final measurements.', 'zeus' ),
			'links'   => array(
				array(
					'label' => __( 'Bathroom Cabinets and Vanities in Orlando: Planning Guide', 'zeus' ),
					'url'   => home_url( '/bathroom-cabinets-vanities-orlando-guide/' ),
				),
				array(
					'label' => __( 'Quartz vs Granite vs Porcelain vs Marble Countertops', 'zeus' ),
					'url'   => home_url( '/quartz-vs-granite-vs-porcelain-vs-marble-countertops/' ),
				),
				array(
					'label' => __( 'Shaker vs Slim Shaker Cabinets', 'zeus' ),
					'url'   => home_url( '/shaker-vs-slim-shaker-cabinets/' ),
				),
			),
		),
		'home-office' => array(
			'heading' => __( 'Home Office Planning Guides', 'zeus' ),
			'intro'   => __( 'Plan built-in storage, file drawers, work surfaces, lighting, and cabinetry around the way the room will be used.', 'zeus' ),
			'links'   => array(
				array(
					'label' => __( 'Custom Home Office Cabinets in Orlando: Design Guide', 'zeus' ),
					'url'   => home_url( '/custom-home-office-cabinets-orlando-guide/' ),
				),
				array(
					'label' => __( 'Explore Cabinet Styles', 'zeus' ),
					'url'   => home_url( '/cabinet-styles/' ),
				),
			),
		),
		'closets' => array(
			'heading' => __( 'Custom Closet Planning Guides', 'zeus' ),
			'intro'   => __( 'Plan hanging space, drawers, shoe storage, corners, lighting, and finish choices around the items you actually need to store.', 'zeus' ),
			'links'   => array(
				array(
					'label' => __( 'Custom Closets in Orlando: Planning Guide', 'zeus' ),
					'url'   => home_url( '/custom-closets-orlando-planning-guide/' ),
				),
				array(
					'label' => __( 'Explore Cabinet Styles', 'zeus' ),
					'url'   => home_url( '/cabinet-styles/' ),
				),
			),
		),
	);

	$cluster = $clusters[ $page_key ];

	if ( function_exists( 'zeus_section_start' ) && function_exists( 'zeus_section_end' ) ) {
		zeus_section_start(
			array(
				'variant' => 'compact',
				'eyebrow' => __( 'Planning Resources', 'zeus' ),
				'heading' => $cluster['heading'],
				'intro'   => $cluster['intro'],
			)
		);
	} else {
		echo '<section class="zeus-section"><div class="zeus-container">';
		echo '<h2>' . esc_html( $cluster['heading'] ) . '</h2>';
		echo '<p>' . esc_html( $cluster['intro'] ) . '</p>';
	}

	echo '<div class="zeus-grid zeus-grid--2">';
	foreach ( $cluster['links'] as $link ) {
		echo '<p><a href="' . esc_url( $link['url'] ) . '">' . esc_html( $link['label'] ) . '</a></p>';
	}
	echo '</div>';

	if ( function_exists( 'zeus_section_start' ) && function_exists( 'zeus_section_end' ) ) {
		zeus_section_end();
	} else {
		echo '</div></section>';
	}
}
add_action( 'get_footer', 'zeus_render_commercial_seo_cluster_links', 5 );
