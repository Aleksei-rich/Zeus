<?php
/**
 * Small factual-copy and snippet corrections for production-rendered theme text.
 *
 * Kept separate so these wording fixes can be deployed/rolled back without
 * rewriting large templates. No layout or business logic changes.
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
