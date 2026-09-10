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
 * Correct homepage strings that were internally inconsistent or too absolute,
 * plus trim the Cabinet Styles archive title/description for search snippets.
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
