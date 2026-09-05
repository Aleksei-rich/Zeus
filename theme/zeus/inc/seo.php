<?php
/**
 * Native, plugin-free SEO plumbing: title override, meta description,
 * Open Graph, and factual structured data. No AggregateRating/review markup.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_filter_document_title_parts( $parts ) {
	if ( is_singular() ) {
		$override = get_post_meta( get_the_ID(), 'zeus_seo_title', true );
		if ( $override ) {
			$parts = array( 'title' => $override );
		}
	} elseif ( is_home() ) {
		$parts = array( 'title' => __( 'Cabinet & Countertop Blog | Orlando, FL | ZEUS', 'zeus' ) );
	} elseif ( is_post_type_archive( 'project' ) ) {
		$parts = array( 'title' => __( 'Cabinet & Countertop Projects | Orlando, FL | ZEUS', 'zeus' ) );
	} elseif ( is_post_type_archive( 'cabinet_collection' ) ) {
		$parts = array( 'title' => __( 'Cabinet Styles Orlando, FL | Shaker, Slim Shaker & Flat Panel | ZEUS', 'zeus' ) );
	}
	return $parts;
}
add_filter( 'document_title_parts', 'zeus_filter_document_title_parts' );

function zeus_output_robots_meta() {
	if ( is_singular() && get_post_meta( get_the_ID(), 'zeus_noindex', true ) ) {
		echo '<meta name="robots" content="noindex,follow">' . "\n";
	}
}
add_action( 'wp_head', 'zeus_output_robots_meta', 1 );

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
	} elseif ( is_home() ) {
		$description = __( 'Practical cabinet, countertop, design and remodeling guidance from ZEUS Cabinets & Countertops for homeowners in Orlando and Central Florida.', 'zeus' );
		$posts_page  = (int) get_option( 'page_for_posts' );
		$url         = $posts_page ? get_permalink( $posts_page ) : home_url( '/blog/' );
	} elseif ( is_post_type_archive( 'project' ) ) {
		$description = __( 'Explore real ZEUS cabinet and countertop projects across Orlando and Central Florida, including kitchens, bathrooms and custom built-in spaces.', 'zeus' );
		$url         = get_post_type_archive_link( 'project' );
	} elseif ( is_post_type_archive( 'cabinet_collection' ) ) {
		$description = __( 'Explore Shaker, Slim Shaker, Brooklyn and Euro flat-panel cabinet styles for Orlando kitchens, bathrooms and custom spaces, with selection and installation coordinated through ZEUS.', 'zeus' );
		$url         = get_post_type_archive_link( 'cabinet_collection' );
	} else {
		$url = home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
	}

	if ( ! $description ) {
		$description = get_bloginfo( 'description' );
	}
	if ( ! $description ) {
		$description = get_bloginfo( 'name' ) . ' — custom cabinets and countertops in Orlando and Central Florida.';
	}

	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );

	// Core handles singular canonicals. Explicitly cover archive/listing pages.
	if ( ( is_home() || is_post_type_archive( array( 'project', 'cabinet_collection' ) ) ) && $url ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
	}

	printf( '<meta property="og:type" content="website">' . "\n" );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
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

function zeus_output_organization_schema() {
	$areas = array( 'Orlando', 'Windermere', 'Winter Garden', 'Horizon West', 'Clermont', 'Dr. Phillips' );

	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'HomeAndConstructionBusiness',
		'@id'        => home_url( '/#organization' ),
		'name'       => 'ZEUS Cabinets & Countertops',
		'legalName'  => 'Zeus Business LLC',
		'url'        => home_url( '/' ),
		'logo'       => ZEUS_THEME_URI . '/assets/img/logo-header.png',
		'telephone'  => '(689) 222-3077',
		'email'      => 'sales@zeuscabinetsflorida.com',
		'founder'    => array(
			'@type' => 'Person',
			'name'  => 'Aleksei Cher',
		),
		'openingHoursSpecification' => array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
				'opens'     => '09:00',
				'closes'    => '19:00',
			),
		),
		'sameAs' => array(
			'https://www.facebook.com/profile.php?id=100083163667410',
			'https://www.instagram.com/zeus.cabinets',
			'https://www.linkedin.com/in/aleksei-cherednichenko-77a862428',
			'https://pin.it/6D4A6LB4j',
			'https://www.youtube.com/@ZeusCabinetsCountertop',
		),
		'areaServed' => array_map(
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
			'@id' => home_url( '/#organization' ),
		),
		'publisher'     => array(
			'@id' => home_url( '/#organization' ),
		),
		'mainEntityOfPage' => get_permalink( $post_id ),
	);
	if ( has_post_thumbnail( $post_id ) ) {
		$schema['image'] = get_the_post_thumbnail_url( $post_id, 'zeus-hero' );
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n"; // phpcs:ignore
}
