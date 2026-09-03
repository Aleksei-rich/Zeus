<?php
/**
 * Google Business Profile API integration for the homepage "What Our
 * Clients Say" section — background-synced only. See docs/DECISIONS.md
 * for the full architecture writeup and the 2026-09-03 policy research
 * this is built on.
 *
 * HARD INVARIANT: nothing in this file may run during normal front-end
 * page rendering. `zeus_get_google_reviews_data()` (called by
 * theme/zeus/front-page.php) only ever reads the cached WP option below
 * — it never calls the API. The only two things that ever call
 * zeus_google_reviews_sync() (the only function that talks to Google)
 * are the ~20-minute WP-Cron event and the `wp zeus google-reviews sync`
 * CLI command.
 *
 * Requires 5 constants, defined ONLY in an untracked, off-webroot
 * production credentials file — never this repository:
 *   ZEUS_GOOGLE_CLIENT_ID
 *   ZEUS_GOOGLE_CLIENT_SECRET
 *   ZEUS_GOOGLE_REFRESH_TOKEN
 *   ZEUS_GOOGLE_ACCOUNT_ID
 *   ZEUS_GOOGLE_LOCATION_ID
 * Intended production path: /home/zeusiwpo/zeus-google-oauth-credentials.php
 * (outside the public web root). This file does not know or care where
 * that file lives — including it is production's wp-config.php's job
 * (see docs/SECURITY-AND-DEPLOYMENT.md), so this plugin stays
 * hosting-agnostic. If the constants are absent, every function below
 * fails safe — see zeus_google_reviews_configured().
 *
 * OAuth token acquisition (the one-time authorization that produces
 * ZEUS_GOOGLE_REFRESH_TOKEN) is performed separately via Google's OAuth
 * Playground with our Web Application OAuth client — this plugin only
 * ever *consumes* an existing refresh token via the standard
 * refresh_token grant; it never runs any authorization-code flow itself.
 *
 * CONTENT HANDLING (2026-09-03 compliance revision): Google's Business
 * Profile APIs content policy permits temporarily storing API content
 * "to improve performance," capped at 30 calendar days -- and content
 * past that window must not merely stop being *displayed*, it must be
 * *removed* from storage. Two things follow from that, enforced
 * throughout this file:
 *   1. API-provided text (reviewer displayName, review comment) is
 *      never rewritten/sanitized/truncated before caching -- it's
 *      stored exactly as Google returned it (only type-validated:
 *      must be a non-empty string) and is only ever escaped
 *      (esc_html()) at render time. Numeric/enum fields (averageRating,
 *      totalReviewCount, starRating) are likewise stored as Google
 *      returned them and only mapped/formatted for display at READ
 *      time (zeus_get_google_reviews_data()), never at fetch/store
 *      time -- the cached wp_options row always reflects Google's
 *      Content verbatim, not a derived/reformatted version of it.
 *   2. Cached Content (review text, names, star ratings, averageRating,
 *      totalReviewCount, updateTime) is actively PURGED from
 *      wp_options once it exceeds ZEUS_GOOGLE_REVIEWS_MAX_AGE_DAYS --
 *      see zeus_google_reviews_purge_expired_content(), called from
 *      both the sync path and the read path, so expired Content cannot
 *      survive in the database even if Google API access has been
 *      broken (and syncs failing) for a month. Only our own technical
 *      sync metadata (last_attempt_at/last_status/last_error/
 *      purged_at) survives a purge -- never any Google-provided
 *      Content. The static Darryl/Gabriel/Jamil fallback array lives in
 *      theme/zeus/front-page.php, not here -- it's owner-supplied
 *      code, not API-cached Google Content, so none of this applies to
 *      it.
 *
 * WP-Cron caveat: the ~20-minute schedule registered below fires on
 * ordinary site traffic (a visitor's page load can trigger a due cron
 * event), not a real system timer — WP-Cron does NOT guarantee exact
 * wall-clock cadence, especially on a low-traffic site. This is fine for
 * a "roughly every 20 minutes" homepage refresh. If production ever
 * needs a real guaranteed cadence, replace WP-Cron's own trigger with an
 * actual cPanel cron job that calls `wp cron event run
 * zeus_google_reviews_sync_event` (or hits wp-cron.php) on a fixed
 * schedule — no code here needs to change for that, since the hook name
 * and callback stay the same either way.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZEUS_GOOGLE_REVIEWS_OPTION', 'zeus_google_reviews_cache' );
define( 'ZEUS_GOOGLE_REVIEWS_MAX_AGE_DAYS', 29 );
define( 'ZEUS_GOOGLE_REVIEWS_CRON_HOOK', 'zeus_google_reviews_sync_event' );
define( 'ZEUS_GOOGLE_REVIEWS_CRON_SCHEDULE', 'zeus_twenty_minutes' );

/**
 * True only when all 5 required constants are defined and non-empty.
 * Every network-touching function below checks this first and fails
 * safe (a WP_Error, never a fatal) when it's false.
 */
function zeus_google_reviews_configured() {
	return defined( 'ZEUS_GOOGLE_CLIENT_ID' ) && ZEUS_GOOGLE_CLIENT_ID
		&& defined( 'ZEUS_GOOGLE_CLIENT_SECRET' ) && ZEUS_GOOGLE_CLIENT_SECRET
		&& defined( 'ZEUS_GOOGLE_REFRESH_TOKEN' ) && ZEUS_GOOGLE_REFRESH_TOKEN
		&& defined( 'ZEUS_GOOGLE_ACCOUNT_ID' ) && ZEUS_GOOGLE_ACCOUNT_ID
		&& defined( 'ZEUS_GOOGLE_LOCATION_ID' ) && ZEUS_GOOGLE_LOCATION_ID;
}

/**
 * Writes the cache option with autoload explicitly disabled -- this
 * review data has no reason to load on every single WP request the way
 * autoloaded options do. Uses add_option() with an explicit autoload
 * flag on first creation (rather than relying solely on update_option()
 * to set autoload on an option that doesn't exist yet, whose behavior
 * has differed across WP versions) so the option is guaranteed
 * non-autoloaded from the moment it's created, on any supported WP
 * version.
 */
function zeus_google_reviews_write_cache( $cache ) {
	if ( false === get_option( ZEUS_GOOGLE_REVIEWS_OPTION, false ) ) {
		add_option( ZEUS_GOOGLE_REVIEWS_OPTION, $cache, '', false );
	} else {
		update_option( ZEUS_GOOGLE_REVIEWS_OPTION, $cache, false );
	}
}

/**
 * ---------------------------------------------------------------------
 * READ SIDE — safe to call from front-end rendering. Never touches the
 * network; only reads (and, on the rare expired-content path, purges)
 * the cached option.
 * ---------------------------------------------------------------------
 */

/**
 * Returns live-synced review data if it exists and is within the
 * 29-day policy window, or false otherwise (meaning: the caller should
 * use its own static fallback content — see theme/zeus/front-page.php).
 * If cached Content has aged past the policy window, this purges it
 * (see zeus_google_reviews_purge_expired_content()) before returning
 * false -- a small DB write on this rare path is expected and correct,
 * not a bug.
 *
 * The mapping from Google's raw stored field names/enums to the shape
 * the theme renders happens here, at read time, on every call -- it
 * never alters the underlying cached Content itself.
 *
 * Shape when truthy:
 *   array(
 *     'rating'  => '4.9',                 // string, 1 decimal
 *     'count'   => 21,                    // int, total review count
 *     'reviews' => array( array( 'name' => .., 'stars' => 1-5, 'text' => .. ), ... up to 3 ),
 *     'source'  => 'api',
 *   )
 */
function zeus_get_google_reviews_data() {
	$cache = get_option( ZEUS_GOOGLE_REVIEWS_OPTION, array() );

	if ( empty( $cache['data'] ) || empty( $cache['fetched_at'] ) || empty( $cache['data']['reviews'] ) ) {
		return false;
	}

	$age_seconds = time() - (int) $cache['fetched_at'];
	if ( $age_seconds > ZEUS_GOOGLE_REVIEWS_MAX_AGE_DAYS * DAY_IN_SECONDS ) {
		zeus_google_reviews_purge_expired_content();
		return false; // Past the policy window -- do not display, no exceptions.
	}

	$raw = $cache['data'];

	$reviews = array();
	foreach ( $raw['reviews'] as $zeus_raw_review ) {
		$reviews[] = array(
			'name'  => $zeus_raw_review['name'],
			'stars' => zeus_google_reviews_star_rating_to_int( $zeus_raw_review['star_rating'] ),
			'text'  => $zeus_raw_review['text'],
		);
	}

	return array(
		'rating'  => null !== $raw['average_rating'] ? number_format( (float) $raw['average_rating'], 1 ) : '',
		'count'   => null !== $raw['total_review_count'] ? (int) $raw['total_review_count'] : 0,
		'reviews' => $reviews,
		'source'  => 'api',
	);
}

/**
 * Removes cached Google Content (review text, reviewer names, star
 * ratings, averageRating, totalReviewCount, updateTime) once it exceeds
 * the 29-day policy window. Leaves technical sync metadata
 * (last_attempt_at/last_status/last_error) untouched, and records a
 * purged_at timestamp. Called from both zeus_get_google_reviews_data()
 * (the read path) and zeus_google_reviews_sync() (the sync path, run
 * unconditionally on every ~20-minute tick regardless of whether that
 * tick's fetch succeeds) -- so expired Content cannot survive even if
 * Google API access is broken (every sync failing) for a month.
 *
 * Returns true if a purge happened, false if there was nothing to
 * purge (no cached data, or cached data still within the window).
 */
function zeus_google_reviews_purge_expired_content() {
	$cache = get_option( ZEUS_GOOGLE_REVIEWS_OPTION, array() );

	if ( empty( $cache['data'] ) || empty( $cache['fetched_at'] ) ) {
		return false;
	}

	$age_seconds = time() - (int) $cache['fetched_at'];
	if ( $age_seconds <= ZEUS_GOOGLE_REVIEWS_MAX_AGE_DAYS * DAY_IN_SECONDS ) {
		return false;
	}

	unset( $cache['data'], $cache['fetched_at'] );
	$cache['purged_at'] = time();

	zeus_google_reviews_write_cache( $cache );

	return true;
}

/**
 * ---------------------------------------------------------------------
 * SYNC SIDE — network calls live here. ONLY ever invoked by WP-Cron
 * (zeus_google_reviews_sync_event) or `wp zeus google-reviews sync`.
 * Never call these from a front-end request.
 * ---------------------------------------------------------------------
 */

/**
 * Exchanges the stored refresh token for a short-lived access token.
 * Returns the access token string, or a WP_Error. Never logs or returns
 * the client secret or refresh token; never echoes the raw token
 * response body back into any error message.
 */
function zeus_google_reviews_get_access_token() {
	if ( ! zeus_google_reviews_configured() ) {
		return new WP_Error( 'zeus_google_reviews_unconfigured', 'Google Reviews credentials are not configured.' );
	}

	$response = wp_remote_post(
		'https://oauth2.googleapis.com/token',
		array(
			'timeout' => 15,
			'body'    => array(
				'grant_type'    => 'refresh_token',
				'client_id'     => ZEUS_GOOGLE_CLIENT_ID,
				'client_secret' => ZEUS_GOOGLE_CLIENT_SECRET,
				'refresh_token' => ZEUS_GOOGLE_REFRESH_TOKEN,
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return new WP_Error( 'zeus_google_reviews_token_http', 'Token refresh request failed: ' . $response->get_error_message() );
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	$body = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( 200 !== $code || ! is_array( $body ) || empty( $body['access_token'] ) ) {
		// Surface only Google's short OAuth error code (e.g. "invalid_grant"),
		// never the raw response body -- keeps this safe to log/display even
		// though, in practice, Google's error responses don't echo secrets back.
		$reason = ( is_array( $body ) && ! empty( $body['error'] ) ) ? sanitize_text_field( $body['error'] ) : ( 'HTTP ' . $code );
		return new WP_Error( 'zeus_google_reviews_token_status', 'Token refresh rejected: ' . $reason );
	}

	return $body['access_token'];
}

/**
 * Maps the Business Profile API's starRating enum to a 1-5 integer.
 * Deliberately a pure read-time mapping (see zeus_get_google_reviews_data())
 * -- the enum string itself is what gets cached, not this integer.
 */
function zeus_google_reviews_star_rating_to_int( $star_rating ) {
	$map = array(
		'ONE'   => 1,
		'TWO'   => 2,
		'THREE' => 3,
		'FOUR'  => 4,
		'FIVE'  => 5,
	);
	return isset( $map[ $star_rating ] ) ? $map[ $star_rating ] : 0;
}

/**
 * Calls GET .../accounts/{accountId}/locations/{locationId}/reviews
 * (pageSize=3, orderBy=updateTime desc). Only the 3 newest reviews are
 * ever requested -- no pagination, by design (see docs/DECISIONS.md).
 *
 * Validates response shape/types only -- does NOT rewrite, truncate,
 * sanitize, or otherwise alter Google's Content. Text fields are stored
 * exactly as returned (only escaped at render time, via esc_html());
 * averageRating/totalReviewCount are stored exactly as Google returned
 * them, never self-calculated; starRating is stored as Google's own
 * enum string, mapped to an integer only at read time.
 *
 * Returns the raw-but-validated array (see the 'data' shape documented
 * on zeus_google_reviews_sync()) or a WP_Error.
 */
function zeus_google_reviews_fetch_from_api() {
	$token = zeus_google_reviews_get_access_token();
	if ( is_wp_error( $token ) ) {
		return $token;
	}

	$url = add_query_arg(
		array(
			'pageSize' => 3,
			'orderBy'  => 'updateTime desc',
		),
		sprintf(
			'https://mybusiness.googleapis.com/v4/accounts/%s/locations/%s/reviews',
			rawurlencode( ZEUS_GOOGLE_ACCOUNT_ID ),
			rawurlencode( ZEUS_GOOGLE_LOCATION_ID )
		)
	);

	$response = wp_remote_get(
		$url,
		array(
			'timeout' => 15,
			'headers' => array(
				'Authorization' => 'Bearer ' . $token,
				'Accept'        => 'application/json',
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return new WP_Error( 'zeus_google_reviews_http', 'Reviews request failed: ' . $response->get_error_message() );
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	if ( 200 !== $code ) {
		return new WP_Error( 'zeus_google_reviews_status', sprintf( 'Reviews request returned HTTP %d.', $code ) );
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( ! is_array( $body ) ) {
		return new WP_Error( 'zeus_google_reviews_json', 'Reviews response was not valid JSON.' );
	}

	$reviews = array();
	if ( ! empty( $body['reviews'] ) && is_array( $body['reviews'] ) ) {
		foreach ( array_slice( $body['reviews'], 0, 3 ) as $zeus_raw_review ) {
			$zeus_comment = $zeus_raw_review['comment'] ?? null;
			if ( ! is_string( $zeus_comment ) || '' === $zeus_comment ) {
				continue; // Star-only reviews with no text have nothing to put in a text card.
			}

			$zeus_display_name = $zeus_raw_review['reviewer']['displayName'] ?? null;
			$zeus_star_rating  = $zeus_raw_review['starRating'] ?? null;
			$zeus_update_time  = $zeus_raw_review['updateTime'] ?? null;

			$reviews[] = array(
				// NOT Google Content: our own fallback label for the case
				// where Google provides no displayName at all (anonymous
				// reviews omit it per the API's own documented behavior) --
				// nothing of Google's is being altered here, since there is
				// no Google-provided name to alter in that case.
				'name'        => ( is_string( $zeus_display_name ) && '' !== $zeus_display_name )
					? $zeus_display_name
					: __( 'Google user', 'zeus-core' ),
				'star_rating' => is_string( $zeus_star_rating ) ? $zeus_star_rating : '',
				'text'        => $zeus_comment,
				'update_time' => is_string( $zeus_update_time ) ? $zeus_update_time : '',
			);
		}
	}

	return array(
		'average_rating'     => ( isset( $body['averageRating'] ) && is_numeric( $body['averageRating'] ) ) ? (float) $body['averageRating'] : null,
		'total_review_count' => ( isset( $body['totalReviewCount'] ) && is_numeric( $body['totalReviewCount'] ) ) ? (int) $body['totalReviewCount'] : null,
		'reviews'            => $reviews,
	);
}

/**
 * The single sync entry point -- called by WP-Cron and by
 * `wp zeus google-reviews sync`. Always purges expired cached Content
 * first (unconditionally, regardless of whether this run's fetch
 * succeeds), then only overwrites the cached review data on a fully
 * successful fetch; on any failure it records the failure (for
 * `wp zeus google-reviews status`) but leaves the last
 * successfully-cached data untouched, so last-known-good survives any
 * number of consecutive failures until it ages out at 29 days on its
 * own (at which point the purge above removes it, not merely hides it
 * from display).
 *
 * Returns true on success, or a WP_Error.
 */
function zeus_google_reviews_sync() {
	zeus_google_reviews_purge_expired_content();

	if ( ! zeus_google_reviews_configured() ) {
		zeus_google_reviews_record_attempt( 'unconfigured', 'Required constants are not defined.' );
		return new WP_Error( 'zeus_google_reviews_unconfigured', 'Google Reviews sync skipped: credentials not configured.' );
	}

	$result = zeus_google_reviews_fetch_from_api();

	if ( is_wp_error( $result ) ) {
		zeus_google_reviews_record_attempt( 'error', $result->get_error_message() );
		return $result;
	}

	$cache                    = get_option( ZEUS_GOOGLE_REVIEWS_OPTION, array() );
	$cache['data']            = $result;
	$cache['fetched_at']      = time();
	$cache['last_attempt_at'] = time();
	$cache['last_status']     = 'success';
	$cache['last_error']      = '';
	zeus_google_reviews_write_cache( $cache );

	return true;
}

/**
 * Records a non-destructive sync attempt (unconfigured/error) without
 * touching the last successfully-cached review data.
 */
function zeus_google_reviews_record_attempt( $status, $message ) {
	$cache                    = get_option( ZEUS_GOOGLE_REVIEWS_OPTION, array() );
	$cache['last_attempt_at'] = time();
	$cache['last_status']     = $status;
	$cache['last_error']      = $message;
	zeus_google_reviews_write_cache( $cache );
}

/**
 * ---------------------------------------------------------------------
 * SCHEDULING
 * ---------------------------------------------------------------------
 */

function zeus_google_reviews_cron_schedules( $schedules ) {
	$schedules[ ZEUS_GOOGLE_REVIEWS_CRON_SCHEDULE ] = array(
		'interval' => 20 * MINUTE_IN_SECONDS,
		'display'  => __( 'Every 20 minutes (ZEUS Google Reviews)', 'zeus-core' ),
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'zeus_google_reviews_cron_schedules' ); // phpcs:ignore WordPress.WP.CronInterval.CronSchedulesInterval

/**
 * Idempotent: safe to call on every `init`. Ensures the cron event is
 * scheduled even if the plugin was updated without a deactivate/
 * reactivate cycle (e.g. a plain code deploy) -- deliberately not
 * relying on the activation hook alone for this.
 */
function zeus_google_reviews_maybe_schedule() {
	if ( ! wp_next_scheduled( ZEUS_GOOGLE_REVIEWS_CRON_HOOK ) ) {
		wp_schedule_event( time(), ZEUS_GOOGLE_REVIEWS_CRON_SCHEDULE, ZEUS_GOOGLE_REVIEWS_CRON_HOOK );
	}
}
add_action( 'init', 'zeus_google_reviews_maybe_schedule' );
add_action( ZEUS_GOOGLE_REVIEWS_CRON_HOOK, 'zeus_google_reviews_sync' );

/**
 * Called from zeus_core_on_deactivation() in zeus-core.php.
 */
function zeus_google_reviews_deactivate() {
	wp_clear_scheduled_hook( ZEUS_GOOGLE_REVIEWS_CRON_HOOK );
}
