<?php
/**
 * Small render/helper functions shared across templates and components.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Honest, unambiguous project status label. Never renders a concept as
 * a completed project — see docs/CONTENT-MODEL.md and
 * .claude/rules/wordpress-development.md.
 */
function zeus_project_status_label( $post_id ) {
	$status = get_post_meta( $post_id, 'zeus_project_status', true );
	return 'completed' === $status
		? __( 'Completed Project', 'zeus' )
		: __( '3D Design Concept', 'zeus' );
}

function zeus_project_is_completed( $post_id ) {
	return 'completed' === get_post_meta( $post_id, 'zeus_project_status', true );
}

function zeus_get_gallery_ids( $post_id, $meta_key = 'zeus_gallery' ) {
	$ids = get_post_meta( $post_id, $meta_key, true );
	return is_array( $ids ) ? array_map( 'absint', $ids ) : array();
}

/**
 * SEO-aware title: explicit override, else the post title.
 */
function zeus_get_seo_title( $post_id ) {
	$override = get_post_meta( $post_id, 'zeus_seo_title', true );
	return $override ? $override : get_the_title( $post_id );
}

function zeus_get_seo_description( $post_id ) {
	$override = get_post_meta( $post_id, 'zeus_seo_description', true );
	if ( $override ) {
		return $override;
	}
	$excerpt = get_the_excerpt( $post_id );
	return $excerpt ? wp_trim_words( wp_strip_all_tags( $excerpt ), 30 ) : '';
}

/**
 * Consistent CTA link target for "Request Free Consultation" — single
 * source of truth so no template hard-codes the URL.
 */
function zeus_consultation_url() {
	$page = function_exists( 'zeus_get_post_by_slug' ) ? zeus_get_post_by_slug( 'consultation', 'page' ) : get_page_by_path( 'consultation' );
	return $page ? get_permalink( $page ) : home_url( '/consultation/' );
}

/**
 * Real, verified ZEUS contact details — sourced read-only from the old
 * site's business-info fields during the Phase 3.5 inventory (see
 * docs/OLD-SITE-INVENTORY.md). Centralized here (not hard-coded per
 * template) so a future correction only needs one edit.
 */
function zeus_phone_number_display() {
	return '(689) 222-3077';
}

function zeus_phone_number_href() {
	return 'tel:+16892223077';
}

function zeus_email_address() {
	return 'sales@zeuscabinetsflorida.com';
}

function zeus_business_hours() {
	return __( 'Monday–Friday, 9:00 AM–7:00 PM', 'zeus' );
}

function zeus_social_links() {
	return array(
		'facebook'  => 'https://www.facebook.com/profile.php?id=100083163667410',
		'instagram' => 'https://www.instagram.com/zeus.cabinets',
		'linkedin'  => 'https://www.linkedin.com/in/aleksei-cherednichenko-77a862428',
		'pinterest' => 'https://pin.it/6D4A6LB4j',
		'youtube'   => 'https://www.youtube.com/@ZeusCabinetsCountertop',
	);
}

/**
 * Display labels for zeus_social_links() keys -- kept separate since the
 * URLs are the actual source of truth and labels are presentation-only.
 */
function zeus_social_labels() {
	return array(
		'facebook'  => __( 'Facebook', 'zeus' ),
		'instagram' => __( 'Instagram', 'zeus' ),
		'linkedin'  => __( 'LinkedIn', 'zeus' ),
		'pinterest' => __( 'Pinterest', 'zeus' ),
		'youtube'   => __( 'YouTube', 'zeus' ),
	);
}

/**
 * Section wrapper component — standardizes the outer <section> +
 * container markup used by every homepage/landing section.
 */
function zeus_section_start( $args = array() ) {
	$variant   = $args['variant'] ?? ''; // '', 'stone', 'navy', 'tight' -- space-separated to combine, e.g. 'stone compact'
	$container = $args['container'] ?? ''; // '', 'narrow', 'wide'
	$id        = $args['id'] ?? '';

	$section_classes = array( 'zeus-section' );
	foreach ( preg_split( '/\s+/', trim( $variant ) ) as $zeus_variant_part ) {
		if ( $zeus_variant_part ) {
			$section_classes[] = 'zeus-section--' . sanitize_html_class( $zeus_variant_part );
		}
	}
	$container_classes = array( 'zeus-container' );
	if ( $container ) {
		$container_classes[] = 'zeus-container--' . sanitize_html_class( $container );
	}

	printf(
		'<section class="%1$s"%2$s><div class="%3$s">',
		esc_attr( implode( ' ', $section_classes ) ),
		$id ? ' id="' . esc_attr( $id ) . '"' : '',
		esc_attr( implode( ' ', $container_classes ) )
	);

	if ( ! empty( $args['eyebrow'] ) || ! empty( $args['heading'] ) || ! empty( $args['intro'] ) ) {
		echo '<div class="zeus-section__header">';
		if ( ! empty( $args['eyebrow'] ) ) {
			echo '<p class="zeus-section__eyebrow">' . esc_html( $args['eyebrow'] ) . '</p>';
		}
		if ( ! empty( $args['heading'] ) ) {
			$level = isset( $args['heading_level'] ) ? absint( $args['heading_level'] ) : 2;
			$level = $level >= 2 && $level <= 4 ? $level : 2;
			echo "<h{$level}>" . esc_html( $args['heading'] ) . "</h{$level}>"; // phpcs:ignore
		}
		if ( ! empty( $args['intro'] ) ) {
			echo '<p>' . esc_html( $args['intro'] ) . '</p>';
		}
		echo '</div>';
	}
}

function zeus_section_end() {
	echo '</div></section>';
}

/**
 * Minimal inline SVG icon set — no icon font/plugin dependency.
 */
function zeus_icon( $name ) {
	$icons = array(
		'phone'    => '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M6.6 10.8c1.4 2.8 3.8 5.2 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.4c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>',
		'chevron'  => '<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M9 6l6 6-6 6"/></svg>',
		'menu'     => '<svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M3 6h18v2H3zM3 11h18v2H3zM3 16h18v2H3z"/></svg>',
		'close'    => '<svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" fill="none"/></svg>',
		'check'    => '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M9 16.2l-3.5-3.5L4 14.2l5 5 11-11-1.5-1.5z"/></svg>',
		'location' => '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 2a7 7 0 00-7 7c0 5.3 7 13 7 13s7-7.7 7-13a7 7 0 00-7-7zm0 9.5A2.5 2.5 0 1112 6.5a2.5 2.5 0 010 5z"/></svg>',
		'star'     => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 2.5l2.9 6.6 7.1.6-5.4 4.7 1.7 7-6.3-3.8-6.3 3.8 1.7-7-5.4-4.7 7.1-.6z"/></svg>',
		'facebook' => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path fill="currentColor" d="M22 12.06C22 6.51 17.52 2 12 2S2 6.51 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.51 1.49-3.9 3.77-3.9 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.91h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94z"/></svg>',
		'instagram' => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="4.2" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="17.3" cy="6.7" r="1.15" fill="currentColor"/></svg>',
		'linkedin' => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path fill="currentColor" d="M20.45 20.45h-3.55v-5.57c0-1.33-.02-3.04-1.85-3.04-1.86 0-2.14 1.45-2.14 2.94v5.67H9.5V9h3.41v1.56h.05c.47-.9 1.63-1.85 3.36-1.85 3.6 0 4.27 2.37 4.27 5.45v6.29zM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12zM7.12 20.45H3.56V9h3.56v11.45z"/></svg>',
		'pinterest' => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 2a10 10 0 0 0-3.65 19.31c-.05-.83-.01-1.83.2-2.73.23-.96 1.52-6.43 1.52-6.43s-.39-.78-.39-1.93c0-1.81 1.05-3.16 2.36-3.16 1.11 0 1.65.84 1.65 1.84 0 1.12-.71 2.8-1.08 4.35-.31 1.3.65 2.36 1.93 2.36 2.32 0 4.1-2.44 4.1-5.97 0-3.12-2.24-5.3-5.44-5.3-3.71 0-5.88 2.78-5.88 5.66 0 1.12.43 2.32.97 2.97a.39.39 0 0 1 .09.37c-.1.42-.32 1.3-.37 1.48-.06.24-.19.29-.44.18-1.65-.77-2.68-3.18-2.68-5.12 0-4.17 3.03-8 8.73-8 4.59 0 8.15 3.27 8.15 7.63 0 4.55-2.87 8.21-6.85 8.21-1.34 0-2.6-.7-3.03-1.52l-.82 3.14c-.3 1.14-1.1 2.58-1.64 3.45A10 10 0 1 0 12 2z"/></svg>',
		'youtube'  => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><rect x="2" y="5" width="20" height="14" rx="4" fill="none" stroke="currentColor" stroke-width="1.8"/><path fill="currentColor" d="M10 8.7l6 3.3-6 3.3z"/></svg>',
	);
	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}
