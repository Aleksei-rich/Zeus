<?php
/**
 * SEO metadata for category archives.
 *
 * The base SEO module intentionally handles singular content and the main
 * post/CPT archives. Category archives need their own term description and
 * self-canonical instead of inheriting the generic site description.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Replace the generic head-meta callback only on category archives.
 */
function zeus_category_seo_bootstrap() {
	if ( ! is_category() ) {
		return;
	}

	remove_action( 'wp_head', 'zeus_output_head_meta' );
	add_action( 'wp_head', 'zeus_output_category_head_meta' );
}
add_action( 'wp', 'zeus_category_seo_bootstrap' );

/**
 * Output category-specific description, canonical and social metadata while
 * retaining the same factual organization and breadcrumb schema as the base
 * SEO module.
 */
function zeus_output_category_head_meta() {
	$term = get_queried_object();
	if ( ! $term || is_wp_error( $term ) ) {
		return;
	}

	$description = trim( wp_strip_all_tags( term_description( $term ) ) );
	if ( ! $description ) {
		$description = sprintf(
			/* translators: %s is the category name. */
			__( 'Practical %s from ZEUS Cabinets & Countertops for homeowners in Orlando and Central Florida.', 'zeus' ),
			strtolower( single_cat_title( '', false ) )
		);
	}

	$paged = max( 1, absint( get_query_var( 'paged' ) ) );
	$url   = 1 < $paged ? get_pagenum_link( $paged ) : get_category_link( $term );
	$title = wp_get_document_title();

	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
	printf( '<meta property="og:type" content="website">' . "\n" );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	printf( '<meta name="twitter:card" content="summary">' . "\n" );

	zeus_output_organization_schema();
	zeus_output_breadcrumb_schema();
}
