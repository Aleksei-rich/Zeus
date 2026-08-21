<?php
/**
 * Native, plugin-free SEO plumbing: title override, meta description,
 * Open Graph, and factual structured data (Organization, LocalBusiness,
 * BreadcrumbList, Article). No AggregateRating/review markup — see
 * docs/SEO-STRATEGY.md ("never fabricate review/rating markup").
 *
 * Canonical URLs are left to WordPress core's built-in rel_canonical()
 * (enabled by default since WP 4.6) rather than duplicated here.
 *
 * A dedicated SEO plugin (sitemaps, redirect management) is deliberately
 * deferred — see docs/DECISIONS.md, "Phase 2: no plugins installed...".
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Title tag override via the SEO meta field, when set. */
function zeus_filter_document_title_parts( $parts ) {
	if ( is_singular() ) {
		$override = get_post_meta( get_the_ID(), 'zeus_seo_title', true );
		if ( $override ) {
			$parts['title'] = $override;
		}
	}
	return $parts;
}
add_filter( 'document_title_parts', 'zeus_filter_document_title_parts' );

/* Explicit noindex for utility pages (e.g. the thank-you page). */
function zeus_output_robots_meta() {
	if ( is_singular() && get_post_meta( get_the_ID(), 'zeus_noindex', true ) ) {
		echo '<meta name="robots" content="noindex,follow">' . "\n";
	}
}
add_action( 'wp_head', 'zeus_output_robots_meta', 1 );

/* Meta description + Open Graph + JSON-LD, all in one wp_head hook. */
function zeus_output_head_meta() {

	$description = '';
	$image_url   = '';
	$url         = '';
	$title       = wp_get_document_title();

	if ( is_singular() ) {
		$post_id     = get_the_ID();
		$description = zeus_get_seo_description( $post_id );
		$url         = get_permalink( $post_id );
		if ( has_post_thumbnail( $post_id ) ) {
			$image_url = get_the_post_thumbnail_url( $post_id, 'zeus-hero' );
		}
	} elseif ( is_front_page() ) {
		$description = get_bloginfo( 'description' );
		$url         = home_url( '/' );
	} else {
		$url = home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
	}

	if ( $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	}

	printf( '<meta property="og:type" content="website">' . "\n" );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	if ( $description ) {
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	}
	if ( $url ) {
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	}
	if ( $image_url ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image_url ) );
	}
	printf( '<meta name="twitter:card" content="%s">' . "\n", $image_url ? 'summary_large_image' : 'summary' );

	zeus_output_organization_schema();
	zeus_output_breadcrumb_schema();

	if ( is_singular( 'post' ) ) {
		zeus_output_article_schema();
	}
}
add_action( 'wp_head', 'zeus_output_head_meta' );

/**
 * Organization / LocalBusiness — sitewide, only factual fields. No
 * street-address storefront implied (service-area business, no
 * walk-in showroom) — see docs/SEO-STRATEGY.md.
 */
function zeus_output_organization_schema() {
	$areas = array( 'Orlando', 'Windermere', 'Winter Garden', 'Horizon West', 'Clermont', 'Dr. Phillips' );

	$schema = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'HomeAndConstructionBusiness',
		'name'             => get_bloginfo( 'name' ),
		'url'              => home_url( '/' ),
		'areaServed'       => array_map(
			function ( $area ) {
				return array(
					'@type' => 'City',
					'name'  => $area,
				);
			},
			$areas
		),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n"; // phpcs:ignore
}

function zeus_output_breadcrumb_schema() {
	if ( is_front_page() ) {
		return;
	}
	$trail = zeus_get_breadcrumb_trail();
	if ( count( $trail ) < 2 ) {
		return;
	}

	$items = array();
	foreach ( $trail as $i => $crumb ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => $crumb['label'],
			'item'     => $crumb['url'] ? $crumb['url'] : ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // phpcs:ignore
		);
	}

	$schema = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $items,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n"; // phpcs:ignore
}

function zeus_output_article_schema() {
	$post_id = get_the_ID();
	$schema  = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'BlogPosting',
		'headline'      => get_the_title( $post_id ),
		'datePublished' => get_the_date( 'c', $post_id ),
		'dateModified'  => get_the_modified_date( 'c', $post_id ),
		'author'        => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
		),
		'mainEntityOfPage' => get_permalink( $post_id ),
	);
	if ( has_post_thumbnail( $post_id ) ) {
		$schema['image'] = get_the_post_thumbnail_url( $post_id, 'zeus-hero' );
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n"; // phpcs:ignore
}
