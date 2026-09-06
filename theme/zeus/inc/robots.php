<?php
/**
 * Public crawler policy for the ZEUS marketing site.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_filter_robots_txt( $output, $public ) {
	if ( ! $public ) {
		return $output;
	}

	$rules = array(
		'',
		'# ZEUS: public search and AI discovery crawlers',
		'User-agent: Googlebot',
		'Allow: /',
		'',
		'User-agent: Bingbot',
		'Allow: /',
		'',
		'User-agent: GPTBot',
		'Allow: /',
		'',
		'User-agent: OAI-SearchBot',
		'Allow: /',
		'',
		'User-agent: ChatGPT-User',
		'Allow: /',
		'',
		'User-agent: ClaudeBot',
		'Allow: /',
		'',
		'User-agent: PerplexityBot',
		'Allow: /',
		'',
		'Sitemap: ' . home_url( '/wp-sitemap.xml' ),
	);

	return rtrim( $output ) . "\n" . implode( "\n", $rules ) . "\n";
}
add_filter( 'robots_txt', 'zeus_filter_robots_txt', 20, 2 );
