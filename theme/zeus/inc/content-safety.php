<?php
/**
 * Small factual-copy, snippet, and homepage content-structure corrections.
 *
 * Kept separate so production-safe corrections can be deployed/rolled back
 * without rewriting large page templates.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Correct a few production-rendered strings that were internally inconsistent,
 * too absolute, or unnecessarily long for search snippets.
 */
function zeus_content_safety_gettext( $translation, $text, $domain ) {
	if ( 'zeus' !== $domain ) {
		return $translation;
	}

	if ( is_front_page() ) {
		$replacements = array(
			'Popular Styles, Ready to Move' => 'Popular Cabinet Styles & Finishes',
			'From transitional Brooklyn to Slim Shaker Oslo — including OSLO Classic Walnut — these collections are stocked for fast turnaround.' => 'From transitional Brooklyn to Slim Shaker Oslo — including OSLO Classic Walnut — explore cabinet styles and finishes for your project. Stock availability varies by collection.',
			'Popular styles ship fast from our warehouse; custom cabinetry covers everything else.' => 'Popular in-stock styles can move faster from our warehouse; custom cabinetry is available for non-standard dimensions and individual solutions.',
			'You get a clear estimate before work begins, so there are no surprises on the final invoice.' => 'You get a clear estimate before work begins so the project scope and pricing are documented up front.',
		);

		return isset( $replacements[ $text ] ) ? $replacements[ $text ] : $translation;
	}

	if ( is_page( 'cabinets' ) ) {
		$replacements = array(
			'Popular styles and finishes available through our central warehouse, helping projects move from selection to installation efficiently — Shaker, Slim Shaker Oslo, Brooklyn, and Euro / Flat Panel.' => 'Popular Shaker, Slim Shaker Oslo, and Brooklyn styles and finishes are available through our central warehouse, helping projects move from selection to installation efficiently. Euro / Flat Panel is available separately and is not kept in stock.',
			'In-Stock Cabinet Collections' => 'Popular Cabinet Collections',
			'From transitional Brooklyn to Slim Shaker Oslo — including OSLO Classic Walnut — these collections are stocked for efficient turnaround.' => 'Compare Brooklyn, Shaker, Slim Shaker Oslo — including OSLO Classic Walnut — and Euro / Flat Panel. Stock availability varies by collection; Euro / Flat Panel is not kept in stock.',
		);

		return isset( $replacements[ $text ] ) ? $replacements[ $text ] : $translation;
	}

	if ( is_singular( 'cabinet_collection' ) && 'euro-flat-panel' === get_post_field( 'post_name', get_queried_object_id() ) ) {
		$replacements = array(
			'Yes — beyond in-stock options, ZEUS can build custom flat-panel cabinetry for built-ins and non-standard spaces.' => 'Yes — ZEUS can build custom flat-panel cabinetry for built-ins, non-standard dimensions, and projects that need an individual solution.',
		);

		return isset( $replacements[ $text ] ) ? $replacements[ $text ] : $translation;
	}

	if ( is_post_type_archive( 'cabinet_collection' ) ) {
		$replacements = array(
			'Cabinet Styles Orlando, FL | Shaker, Slim Shaker & Flat Panel | ZEUS' => 'Cabinet Styles Orlando, FL | Shaker & Flat Panel | ZEUS',
			'Explore Shaker, Slim Shaker, Brooklyn and Euro flat-panel cabinet styles for Orlando kitchens, bathrooms and custom spaces, with selection and installation coordinated through ZEUS.' => 'Compare Shaker, Slim Shaker, Brooklyn and Euro flat-panel cabinet styles for Orlando kitchens, bathrooms and custom spaces with ZEUS.',
		);

		return isset( $replacements[ $text ] ) ? $replacements[ $text ] : $translation;
	}

	return $translation;
}
add_filter( 'gettext', 'zeus_content_safety_gettext', 25, 3 );

/**
 * The homepage historically rendered two consecutive sections for the same
 * business proof: a dynamic Portfolio/Featured Projects section and a second
 * static "Real ZEUS Work" photo strip. Keep one source of truth by retaining
 * the dynamic Project CPT section, relabeling its eyebrow as "Real ZEUS Work",
 * and removing the redundant static photo section from the server-rendered
 * HTML. The full Portfolio archive and its project URLs remain unchanged.
 */
function zeus_unify_home_real_work_html( $html ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}

	// Relabel only the section that contains the Featured Projects heading.
	$featured_pos = strpos( $html, 'Featured Projects' );
	if ( false !== $featured_pos ) {
		$featured_start = strrpos( substr( $html, 0, $featured_pos ), '<section' );
		$featured_end   = strpos( $html, '</section>', $featured_pos );

		if ( false !== $featured_start && false !== $featured_end ) {
			$featured_end += strlen( '</section>' );
			$featured      = substr( $html, $featured_start, $featured_end - $featured_start );
			$featured      = str_replace( '>Portfolio<', '>Real ZEUS Work<', $featured );
			$html          = substr( $html, 0, $featured_start ) . $featured . substr( $html, $featured_end );
		}
	}

	// Remove the now-redundant static Real ZEUS Installations section entirely.
	$legacy_pos = strpos( $html, 'From Real ZEUS Installations' );
	if ( false !== $legacy_pos ) {
		$legacy_start = strrpos( substr( $html, 0, $legacy_pos ), '<section' );
		$legacy_end   = strpos( $html, '</section>', $legacy_pos );

		if ( false !== $legacy_start && false !== $legacy_end ) {
			$legacy_end += strlen( '</section>' );
			$html        = substr( $html, 0, $legacy_start ) . substr( $html, $legacy_end );
		}
	}

	return $html;
}

/**
 * Start buffering on the `wp` hook rather than late in `template_redirect`.
 * Some full-page cache layers can serve/exit during template_redirect before a
 * late callback runs. Starting here ensures the final HTML is still filtered
 * even when that cache layer handles the response later in the request.
 */
function zeus_unify_home_real_work_start_buffer() {
	if ( is_admin() || ! is_front_page() ) {
		return;
	}

	ob_start( 'zeus_unify_home_real_work_html' );
}
add_action( 'wp', 'zeus_unify_home_real_work_start_buffer', 0 );
