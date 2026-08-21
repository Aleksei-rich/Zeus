<?php
/**
 * WP-CLI: `wp zeus seed` — the explicit, controlled way to run initial
 * content setup. Only loaded when WP-CLI is present (see zeus-core.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Zeus_Seed_Command {

	/**
	 * Runs initial content setup: taxonomy terms, cabinet collections,
	 * standard pages, and nav menus. Idempotent and registry-guarded —
	 * safe to run repeatedly; never recreates deleted content.
	 *
	 * ## EXAMPLES
	 *
	 *     wp zeus seed
	 *
	 * @when after_wp_load
	 */
	public function __invoke( $args, $assoc_args ) {
		$results = zeus_seed_all();

		foreach ( $results as $category => $created ) {
			if ( $created ) {
				WP_CLI::log( ucfirst( $category ) . ': ' . implode( ', ', $created ) );
			} else {
				WP_CLI::log( ucfirst( $category ) . ': nothing new (already seeded)' );
			}
		}

		WP_CLI::success( 'ZEUS initial content setup complete.' );
	}
}

WP_CLI::add_command( 'zeus seed', 'Zeus_Seed_Command' );
