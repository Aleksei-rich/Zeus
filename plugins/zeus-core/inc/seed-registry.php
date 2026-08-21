<?php
/**
 * Seed registry — the safety net behind every seed function in
 * inc/seeding.php.
 *
 * Problem this solves: a naive "seed if it doesn't exist" check (via
 * get_page_by_path()/get_page_by_path() for a CPT, etc.) is idempotent
 * against double-creation, but NOT safe against resurrection — if an
 * owner deliberately deletes a seeded page and someone re-runs the seed
 * command later, "doesn't exist" is true again and it gets silently
 * recreated. That is explicitly disallowed by the seeding safety audit.
 *
 * This registry is a permanent, append-only record (one wp_option) of
 * every slug/term/menu this plugin has EVER created. Every seed
 * function must check zeus_seed_registry_has() before creating
 * anything, and call zeus_seed_registry_mark() immediately after a
 * successful create — never on failure. Once marked, an entry is never
 * un-marked automatically; only a deliberate site-owner action (editing
 * the option directly, or a future "reset" tool) can make a slug
 * eligible for re-seeding.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZEUS_SEED_REGISTRY_OPTION', 'zeus_seed_registry' );

function zeus_seed_registry_get() {
	$registry = get_option( ZEUS_SEED_REGISTRY_OPTION, array() );
	return is_array( $registry ) ? $registry : array();
}

function zeus_seed_registry_has( $category, $key ) {
	$registry = zeus_seed_registry_get();
	return ! empty( $registry[ $category ][ $key ] );
}

function zeus_seed_registry_mark( $category, $key ) {
	$registry = zeus_seed_registry_get();
	if ( ! isset( $registry[ $category ] ) || ! is_array( $registry[ $category ] ) ) {
		$registry[ $category ] = array();
	}
	$registry[ $category ][ $key ] = current_time( 'mysql' );
	update_option( ZEUS_SEED_REGISTRY_OPTION, $registry, false );
}

/**
 * Finds a post purely by post_name (slug), regardless of hierarchy
 * depth. Deliberately NOT get_page_by_path(): that function requires
 * the *full* ancestor-to-leaf path for non-top-level pages, and
 * silently returns null for a child page's bare slug — which would
 * make every seed function above treat existing child content as
 * missing and recreate it (this was caught during the seeding safety
 * audit; see docs/DECISIONS.md, "Seeding safety fix"). Our seeded
 * slugs are unique site-wide by design, so a plain post_name lookup is
 * the correct and safe check.
 */
function zeus_get_post_by_slug( $slug, $post_type ) {
	$posts = get_posts(
		array(
			'name'                   => $slug,
			'post_type'              => $post_type,
			'post_status'            => 'any',
			'numberposts'            => 1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);
	return $posts ? $posts[0] : null;
}
