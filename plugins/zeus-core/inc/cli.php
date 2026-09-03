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

/**
 * WP-CLI: `wp zeus google-reviews sync` / `wp zeus google-reviews
 * status` — manual control over the Google Business Profile reviews
 * sync that otherwise runs on a ~20-minute WP-Cron schedule. See
 * plugins/zeus-core/inc/google-reviews.php.
 */
class Zeus_Google_Reviews_Command {

	/**
	 * Runs a Google Business Profile reviews sync immediately. Only
	 * overwrites cached review data on a fully successful response --
	 * see plugins/zeus-core/inc/google-reviews.php for the fallback
	 * logic.
	 *
	 * ## EXAMPLES
	 *
	 *     wp zeus google-reviews sync
	 *
	 * @when after_wp_load
	 */
	public function sync( $args, $assoc_args ) {
		if ( ! function_exists( 'zeus_google_reviews_sync' ) ) {
			WP_CLI::error( 'Google Reviews integration is not loaded.' );
		}

		$result = zeus_google_reviews_sync();

		if ( is_wp_error( $result ) ) {
			WP_CLI::error( $result->get_error_message() );
		}

		WP_CLI::success( 'Google Reviews synced.' );
	}

	/**
	 * Shows whether credentials are configured, the last sync
	 * attempt/success, cached data age, and whether the homepage is
	 * currently showing live API data or static fallback content.
	 *
	 * ## EXAMPLES
	 *
	 *     wp zeus google-reviews status
	 *
	 * @when after_wp_load
	 */
	public function status( $args, $assoc_args ) {
		if ( ! function_exists( 'zeus_google_reviews_configured' ) ) {
			WP_CLI::error( 'Google Reviews integration is not loaded.' );
		}

		WP_CLI::log( 'Configured: ' . ( zeus_google_reviews_configured() ? 'yes' : 'no' ) );

		$cache = get_option( 'zeus_google_reviews_cache', array() );

		if ( empty( $cache['last_attempt_at'] ) ) {
			WP_CLI::log( 'No sync attempt has run yet.' );
			return;
		}

		WP_CLI::log( 'Last attempt: ' . gmdate( 'Y-m-d H:i:s', $cache['last_attempt_at'] ) . ' UTC (' . $cache['last_status'] . ')' );
		if ( ! empty( $cache['last_error'] ) ) {
			WP_CLI::log( 'Last error: ' . $cache['last_error'] );
		}

		if ( empty( $cache['fetched_at'] ) || empty( $cache['data'] ) ) {
			if ( ! empty( $cache['purged_at'] ) ) {
				WP_CLI::log( sprintf( 'Cached Google Content was purged %s UTC (past the %d-day policy window) -- homepage is using static fallback content.', gmdate( 'Y-m-d H:i:s', $cache['purged_at'] ), ZEUS_GOOGLE_REVIEWS_MAX_AGE_DAYS ) );
			} else {
				WP_CLI::log( 'No successful sync yet -- homepage is using static fallback content.' );
			}
			return;
		}

		$age_days = ( time() - $cache['fetched_at'] ) / DAY_IN_SECONDS;
		WP_CLI::log( sprintf( 'Last successful sync: %s UTC (%.1f days ago)', gmdate( 'Y-m-d H:i:s', $cache['fetched_at'] ), $age_days ) );
		WP_CLI::log(
			$age_days <= ZEUS_GOOGLE_REVIEWS_MAX_AGE_DAYS
				? 'Cached Content is fresh -- homepage is using live API data.'
				: sprintf( 'Cached Content exceeds the %d-day policy window -- next sync/read will purge it and the homepage will revert to static fallback content.', ZEUS_GOOGLE_REVIEWS_MAX_AGE_DAYS )
		);

		if ( ! empty( $cache['data']['total_review_count'] ) ) {
			WP_CLI::log(
				sprintf(
					'Cached rating: %s (%d total reviews on Google), %d review(s) with text cached.',
					$cache['data']['average_rating'] ?? '?',
					$cache['data']['total_review_count'],
					count( $cache['data']['reviews'] ?? array() )
				)
			);
		}
	}
}

WP_CLI::add_command( 'zeus google-reviews', 'Zeus_Google_Reviews_Command' );
