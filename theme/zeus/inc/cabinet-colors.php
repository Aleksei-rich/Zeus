<?php
/**
 * Presentation-side wiring for Cabinet Style -> Color pages. Content and
 * routing (rewrite rule, query var, 404 gating) live in the zeus-core
 * plugin (inc/cabinet-colors.php) -- this file only decides which
 * template renders a validated color URL and exposes a couple of small
 * helpers other theme files (breadcrumbs, SEO, the color template
 * itself) use to detect "am I on a color page." See
 * docs/CONTENT-MODEL.md.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_is_cabinet_color_page() {
	return is_singular( 'cabinet_collection' ) && (bool) get_query_var( 'zeus_cabinet_color' );
}

/**
 * The current color's curated content, or null off a color page (or on
 * one whose combination somehow isn't published -- the plugin's
 * pre_get_posts gate already 404s that case before a template loads,
 * but callers still get a safe null rather than a fatal).
 */
function zeus_get_current_cabinet_color_content() {
	if ( ! zeus_is_cabinet_color_page() ) {
		return null;
	}
	return zeus_get_cabinet_color_content( get_post_field( 'post_name' ), get_query_var( 'zeus_cabinet_color' ) );
}

function zeus_cabinet_color_template_include( $template ) {
	if ( zeus_is_cabinet_color_page() ) {
		$found = locate_template( 'single-cabinet-color.php' );
		if ( $found ) {
			return $found;
		}
	}
	return $template;
}
add_filter( 'template_include', 'zeus_cabinet_color_template_include', 20 );
