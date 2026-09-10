<?php
/**
 * Small factual-copy corrections for production-rendered theme text.
 *
 * Kept separate so these wording fixes can be deployed/rolled back without
 * rewriting the large homepage template. No layout or business logic changes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Correct a few homepage strings that were internally inconsistent or too
 * absolute for a professional service-site claim.
 */
function zeus_content_safety_gettext( $translation, $text, $domain ) {
	if ( 'zeus' !== $domain || ! is_front_page() ) {
		return $translation;
	}

	$replacements = array(
		'Popular Styles, Ready to Move' => 'Popular Cabinet Styles & Finishes',
		'From transitional Brooklyn to Slim Shaker Oslo — including OSLO Classic Walnut — these collections are stocked for fast turnaround.' => 'From transitional Brooklyn to Slim Shaker Oslo — including OSLO Classic Walnut — explore cabinet styles and finishes for your project. Stock availability varies by collection.',
		'Popular styles ship fast from our warehouse; custom cabinetry covers everything else.' => 'Popular in-stock styles can move faster from our warehouse; custom cabinetry is available for non-standard dimensions and individual solutions.',
		'You get a clear estimate before work begins, so there are no surprises on the final invoice.' => 'You get a clear estimate before work begins so the project scope and pricing are documented up front.',
	);

	return isset( $replacements[ $text ] ) ? $replacements[ $text ] : $translation;
}
add_filter( 'gettext', 'zeus_content_safety_gettext', 25, 3 );
