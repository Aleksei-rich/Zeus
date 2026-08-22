<?php
/**
 * Idempotent, registry-guarded initial-content seed functions.
 *
 * IMPORTANT: nothing in this file is hooked to `init`, `activate_*`, or
 * any other automatic WordPress event. Seeding only runs when explicitly
 * triggered — via `wp zeus seed` (see inc/cli.php) or the Tools > ZEUS
 * Setup admin page (see inc/admin-tools-page.php) — per the explicit
 * requirement that "production content creation must be an explicit
 * controlled migration/setup action," never something that happens
 * merely because the plugin/theme is active. See docs/DECISIONS.md.
 *
 * Every function here:
 *  - only ever CREATES; it never updates/overwrites an existing post,
 *    so owner edits are never touched;
 *  - checks the permanent seed registry (inc/seed-registry.php) before
 *    creating, so a slug that was seeded once and later deleted by the
 *    owner is never silently recreated;
 *  - is safe to call multiple times in one request or across many runs.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_seed_terms() {
	$results = array();

	$term_sets = array(
		'finish'              => array( 'Fawn', 'Gray', 'Midnight', 'White', 'Pearl', 'Slate', 'Sand', 'Kodiak', 'Moss', 'Oak', 'Walnut' ),
		'project_type'        => array( 'Kitchen', 'Bathroom', 'Closet', 'Laundry & Pantry', 'Home Office' ),
		'service_area'        => array( 'Orlando', 'Windermere', 'Winter Garden', 'Horizon West', 'Clermont', 'Dr. Phillips' ),
		'countertop_material' => array( 'Quartz', 'Granite', 'Porcelain', 'Marble' ),
	);

	foreach ( $term_sets as $taxonomy => $terms ) {
		foreach ( array_unique( $terms ) as $term_name ) {
			$registry_key = $taxonomy . ':' . $term_name;
			if ( zeus_seed_registry_has( 'terms', $registry_key ) ) {
				continue;
			}
			if ( term_exists( $term_name, $taxonomy ) ) {
				// Pre-existing (e.g. owner-created) term with this name —
				// record it as seeded so we never touch it again, but
				// don't claim we created it.
				zeus_seed_registry_mark( 'terms', $registry_key );
				continue;
			}
			$result = wp_insert_term( $term_name, $taxonomy );
			if ( ! is_wp_error( $result ) ) {
				zeus_seed_registry_mark( 'terms', $registry_key );
				$results[] = "$taxonomy: $term_name";
			}
		}
	}

	return $results;
}

function zeus_seed_collections() {
	$results = array();

	$collections = array(
		'brooklyn'        => array(
			'title'        => 'Brooklyn',
			'excerpt'      => 'A full-overlay, transitional cabinet collection with a clean, contemporary profile.',
			'content'      => "<p>The Brooklyn collection is ZEUS's full-overlay, transitional cabinet line — a clean, minimal door profile suited to both modern and classic kitchen and bathroom designs.</p><h2>Design</h2><p>Full-overlay doors cover most of the cabinet's face frame, so what you see is mostly door front with minimal visible frame between doors and drawers. Brooklyn is available in Fawn, Gray, Midnight, White, Pearl, and Slate.</p><p>We'll go over profile, finish, and hardware options together during your <a href=\"/consultation/\">consultation</a>.</p>",
			'profile_type' => 'Full-overlay transitional',
			'finishes'     => array( 'Fawn', 'Gray', 'Midnight', 'White', 'Pearl', 'Slate' ),
			'menu_order'   => 1,
		),
		'shaker'          => array(
			'title'        => 'Shaker',
			'excerpt'      => "ZEUS's traditional Shaker-style cabinet collection, with a classic five-piece recessed-panel door.",
			'content'      => "<p>The Shaker collection is ZEUS's traditional Shaker cabinet line — a classic five-piece recessed-panel door, distinct from the narrower-rail \"Slim Shaker\" profile used on the Oslo collection.</p><h2>Design</h2><p>The Shaker door is built from five pieces — four frame pieces around a flat center panel, recessed slightly below the frame. Shaker is available in White, Sand, Kodiak, and Moss.</p><p>Curious how Shaker compares to Slim Shaker <a href=\"/cabinets/oslo/\">Oslo</a>? We're happy to show you both during your <a href=\"/consultation/\">consultation</a>.</p>",
			'profile_type' => 'Traditional Shaker (five-piece recessed panel)',
			'finishes'     => array( 'White', 'Sand', 'Kodiak', 'Moss' ),
			'menu_order'   => 2,
		),
		'oslo'            => array(
			'title'        => 'Oslo',
			'excerpt'      => 'The Oslo Slim Shaker collection — a narrower-rail Shaker profile, including the Classic Walnut finish.',
			'content'      => "<p>The Oslo collection is ZEUS's Slim Shaker cabinet line — a narrower-rail take on the Shaker door profile, distinct from the traditional Shaker collection. The Walnut finish — <strong>OSLO Classic Walnut Slim Shaker Kitchen Cabinets</strong> — pairs the Slim Shaker profile with a natural walnut finish.</p><h2>Design</h2><p>Oslo keeps the same five-piece recessed-panel geometry as a traditional Shaker door, but with a narrower frame around the center panel. Oslo is available in White, Oak, and Walnut.</p><p>Not sure whether traditional <a href=\"/cabinets/shaker/\">Shaker</a> or Slim Shaker Oslo is right for your kitchen? We'll show you both during your <a href=\"/consultation/\">consultation</a>.</p>",
			'profile_type' => 'Slim Shaker (narrow-rail)',
			'finishes'     => array( 'White', 'Oak', 'Walnut' ),
			'menu_order'   => 3,
		),
		'euro-flat-panel' => array(
			'title'        => 'Euro / Flat Panel',
			'excerpt'      => "ZEUS's European-style flat-panel cabinet collection, for a streamlined, handle-integrated look.",
			'content'      => "<p>The Euro / Flat Panel collection is ZEUS's European-style flat-panel cabinet line, built for a streamlined, minimal-hardware look.</p><h2>Design</h2><p>Flat-panel doors have no raised or recessed detail — a single flat face front to back, which pairs naturally with slab countertops and minimal or handle-less hardware.</p><p>We'll go over finish, hardware, and layout options together during your <a href=\"/consultation/\">consultation</a>.</p>",
			'profile_type' => 'European flat panel',
			'finishes'     => array(),
			'menu_order'   => 4,
		),
	);

	foreach ( $collections as $slug => $data ) {
		if ( zeus_seed_registry_has( 'collections', $slug ) ) {
			continue;
		}
		if ( zeus_get_post_by_slug( $slug, 'cabinet_collection' ) ) {
			zeus_seed_registry_mark( 'collections', $slug );
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'cabinet_collection',
				'post_title'   => $data['title'],
				'post_name'    => $slug,
				'post_excerpt' => $data['excerpt'],
				'post_content' => $data['content'],
				'post_status'  => 'publish',
				'menu_order'   => $data['menu_order'],
			)
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		update_post_meta( $post_id, 'zeus_profile_type', $data['profile_type'] );
		if ( ! empty( $data['finishes'] ) ) {
			wp_set_object_terms( $post_id, $data['finishes'], 'finish' );
		}

		$style_key = 'cabinetry_style:' . $data['title'];
		if ( ! zeus_seed_registry_has( 'terms', $style_key ) && ! term_exists( $data['title'], 'cabinetry_style' ) ) {
			wp_insert_term( $data['title'], 'cabinetry_style', array( 'slug' => $slug ) );
		}
		zeus_seed_registry_mark( 'terms', $style_key );

		zeus_seed_registry_mark( 'collections', $slug );
		$results[] = $data['title'];
	}

	return $results;
}

function zeus_seed_pages() {
	$results     = array();

	$pages = array(
		'cabinets'                  => array( 'title' => 'Cabinets', 'content' => '<p>ZEUS designs and builds custom kitchen and bathroom cabinetry for homes across Orlando and Central Florida.</p><h2>What We Build</h2><p>We build cabinetry for <a href="/cabinets/kitchen-cabinets/">kitchens</a>, <a href="/cabinets/bathroom-cabinets-vanities/">bathrooms</a>, <a href="/custom-spaces/closets/">closets</a>, <a href="/custom-spaces/laundry-pantry/">laundry rooms and pantries</a>, and <a href="/custom-spaces/home-office/">home offices</a> — every project designed around the specific space rather than assembled from stock sizes.</p><p><a href="/consultation/">Request a free consultation</a> to start planning your project.</p>', 'parent' => 0 ),
		'kitchen-cabinets'          => array( 'title' => 'Kitchen Cabinets', 'content' => '<p>Custom kitchen cabinets built around how your kitchen is actually used — storage, layout, and finish all planned together rather than picked from a generic catalog.</p><h2>Designed Around How You Cook</h2><p>Custom kitchen cabinetry means the layout is planned around your actual routine instead of fitting your kitchen into stock cabinet widths.</p><h2>Cabinet Styles for Your Kitchen</h2><p>We build kitchen cabinets in four collections: <a href="/cabinet-styles/brooklyn/">Brooklyn</a>, <a href="/cabinet-styles/shaker/">Shaker</a>, Slim Shaker <a href="/cabinet-styles/oslo/">Oslo</a>, and <a href="/cabinet-styles/euro-flat-panel/">Euro / Flat Panel</a>.</p><p><a href="/consultation/">Request a free consultation</a> to start planning your kitchen.</p>', 'parent' => 'cabinets' ),
		'bathroom-cabinets-vanities' => array( 'title' => 'Bathroom Cabinets & Vanities', 'content' => '<p>Custom bathroom cabinetry and vanities sized and finished for your space, from a single vanity to a full primary-bath remodel.</p><h2>Vanities and Storage in One Plan</h2><p>We plan the cabinetry around your actual storage needs and the room\'s layout, rather than starting from a stock vanity size and working backward.</p><p><a href="/consultation/">Request a free consultation</a> to start planning your bathroom.</p>', 'parent' => 'cabinets' ),
		'countertops'               => array( 'title' => 'Countertops', 'content' => '<p>ZEUS works with quartz, granite, porcelain, and marble countertops — the right material depends on how a kitchen or bathroom is actually used, not just how it looks on day one.</p><h2>Four Materials, Different Tradeoffs</h2><p><a href="/countertops/quartz/">Quartz</a>, <a href="/countertops/granite/">granite</a>, <a href="/countertops/porcelain/">porcelain</a>, and <a href="/countertops/marble/">marble</a> each have different appearance, maintenance, and durability tradeoffs.</p><p><a href="/consultation/">Request a free consultation</a> to talk through countertop options.</p>', 'parent' => 0 ),
		'quartz'                    => array( 'title' => 'Quartz Countertops', 'content' => '<p>Quartz is an engineered stone — quartz mineral bound with resin — which makes it non-porous and highly consistent in pattern and color from slab to slab. It resists staining and doesn\'t require periodic sealing, which makes it a common choice for busy kitchens.</p><h2>Maintenance</h2><p>Day-to-day care is simple: warm water and mild soap. Quartz doesn\'t require periodic resealing the way granite and marble do.</p><p>We\'ll walk through color and pattern options during your <a href="/consultation/">consultation</a>.</p>', 'parent' => 'countertops' ),
		'granite'                   => array( 'title' => 'Granite Countertops', 'content' => '<p>Granite is a natural stone, so each slab is unique in pattern and veining. It\'s heat- and scratch-resistant and, like most natural stone, benefits from periodic sealing to stay stain-resistant over time.</p><h2>Selecting Granite</h2><p>Because pattern and color vary slab to slab, we recommend viewing the actual slab before committing. We\'ll cover sealing expectations during your <a href="/consultation/">consultation</a>.</p>', 'parent' => 'countertops' ),
		'porcelain'                 => array( 'title' => 'Porcelain Countertops', 'content' => '<p>Porcelain slab is a manufactured material fired at very high temperatures, making it dense, non-porous, and highly resistant to heat, scratches, and UV fading — a common choice for outdoor kitchens as well as indoor use.</p><p>We\'ll cover slab thickness and edge treatment together during your <a href="/consultation/">consultation</a>.</p>', 'parent' => 'countertops' ),
		'marble'                    => array( 'title' => 'Marble Countertops', 'content' => '<p>Marble is a natural stone prized for its veining and classic look. It\'s softer and more porous than granite or quartz, so it takes more care around acidic foods and staining — many homeowners choose it for bathrooms or lower-traffic surfaces for that reason.</p><p>If you love the look but want less maintenance, ask us about quartz or porcelain options with marble-style veining during your <a href="/consultation/">consultation</a>.</p>', 'parent' => 'countertops' ),
		'custom-spaces'             => array( 'title' => 'Custom Spaces', 'content' => '<p>Beyond kitchens and bathrooms, ZEUS builds custom cabinetry for closets, laundry and pantry rooms, and home offices.</p><p>The same planning approach applies to <a href="/custom-spaces/closets/">closets</a>, <a href="/custom-spaces/laundry-pantry/">laundry rooms and pantries</a>, and <a href="/custom-spaces/home-office/">home offices</a>.</p><p><a href="/consultation/">Request a free consultation</a> to talk through your space.</p>', 'parent' => 0 ),
		'closets'                   => array( 'title' => 'Custom Closets', 'content' => '<p>Custom closet systems built to fit your space and how you actually store things — not a one-size-fits-all kit.</p><p>We build custom storage for both reach-in closets and larger walk-in spaces. <a href="/consultation/">Request a free consultation</a> to start planning your closet.</p>', 'parent' => 'custom-spaces' ),
		'laundry-pantry'            => array( 'title' => 'Laundry & Pantry', 'content' => '<p>Custom cabinetry for laundry rooms and pantries — built-in storage planned around your appliances and everyday routine.</p><p>We plan cabinetry around your actual appliances and shopping/cooking habits. <a href="/consultation/">Request a free consultation</a> to start planning.</p>', 'parent' => 'custom-spaces' ),
		'home-office'               => array( 'title' => 'Home Office', 'content' => '<p>Custom home office cabinetry and built-ins for a dedicated, organized workspace.</p><p>We build cabinetry and built-ins around your workflow instead of fitting a generic desk-and-shelf kit into the room. <a href="/consultation/">Request a free consultation</a> to get started.</p>', 'parent' => 'custom-spaces' ),
		'about'                     => array( 'title' => 'About', 'content' => '<p>ZEUS Cabinets & Countertops designs and builds custom cabinetry and countertops for homeowners across Orlando and Central Florida.</p><h2>How We Work</h2><p>One team handles design, cabinetry, and countertops together, planned around how you actually use your space.</p><h2>Where We Work</h2><p>ZEUS is a service-area business serving Orlando, Windermere, Winter Garden, Horizon West, Clermont, and Dr. Phillips.</p><p><a href="/consultation/">Request a free consultation</a> to start your project.</p>', 'parent' => 0 ),
		'contact'                   => array( 'title' => 'Contact', 'content' => '<p>ZEUS is a service-area business serving Orlando, Windermere, Winter Garden, Horizon West, Clermont, and Dr. Phillips. Consultations are by appointment — there is no public showroom to walk into.</p><h2>Get in Touch</h2><p>Phone: <a href="tel:+16892223077">(689) 222-3077</a><br>Email: <a href="mailto:sales@zeuscabinetsflorida.com">sales@zeuscabinetsflorida.com</a><br>Hours: Monday–Friday, 9:00 AM–7:00 PM</p><p>The fastest way to start a project is our <a href="/consultation/">free consultation request form</a>.</p>', 'parent' => 0 ),
		'consultation'              => array( 'title' => 'Request Free Consultation', 'content' => '', 'parent' => 0, 'template' => 'templates/template-consultation.php' ),
		'thank-you'                 => array( 'title' => 'Thank You', 'content' => '<p>' . esc_html__( 'Thank you — your request has been received. A member of the ZEUS team will be in touch to schedule your free consultation.', 'zeus-core' ) . '</p>', 'parent' => 0, 'noindex' => true ),
		'blog'                      => array( 'title' => 'Blog', 'content' => '', 'parent' => 0 ),
		'home'                      => array( 'title' => 'Home', 'content' => '', 'parent' => 0 ),
	);

	$slug_to_id = array();
	foreach ( $pages as $slug => $data ) {
		$existing            = zeus_get_post_by_slug( $slug, 'page' );
		$slug_to_id[ $slug ] = $existing ? $existing->ID : 0;
	}

	foreach ( $pages as $slug => $data ) {
		if ( zeus_seed_registry_has( 'pages', $slug ) ) {
			continue;
		}
		if ( $slug_to_id[ $slug ] ) {
			zeus_seed_registry_mark( 'pages', $slug );
			continue;
		}

		$parent_id = is_string( $data['parent'] ) ? ( $slug_to_id[ $data['parent'] ] ?? 0 ) : 0;

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_title'   => $data['title'],
				'post_name'    => $slug,
				'post_content' => $data['content'],
				'post_status'  => 'publish',
				'post_parent'  => $parent_id,
			)
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		$slug_to_id[ $slug ] = $post_id;

		if ( ! empty( $data['template'] ) ) {
			update_post_meta( $post_id, '_wp_page_template', $data['template'] );
		}
		if ( ! empty( $data['noindex'] ) ) {
			update_post_meta( $post_id, 'zeus_noindex', 1 );
		}

		zeus_seed_registry_mark( 'pages', $slug );
		$results[] = $data['title'];
	}

	// Static front page wiring — only set once, only if not already configured.
	if ( ! zeus_seed_registry_has( 'options', 'front_page_wiring' ) ) {
		if ( ! empty( $slug_to_id['blog'] ) && ! empty( $slug_to_id['home'] ) ) {
			update_option( 'page_for_posts', $slug_to_id['blog'] );
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $slug_to_id['home'] );
			zeus_seed_registry_mark( 'options', 'front_page_wiring' );
			$results[] = 'front-page wiring';
		}
	}

	return $results;
}

function zeus_seed_menus() {
	$results = array();

	$menu_specs = array(
		'primary-navigation' => array(
			'label'    => 'Primary Navigation',
			'location' => 'primary',
			'items'    => array(
				array( 'title' => 'Home', 'page' => 'home' ),
				array(
					'title' => 'Cabinets',
					'page'  => 'cabinets',
					'children' => array(
						array( 'title' => 'Kitchen Cabinets', 'page' => 'kitchen-cabinets' ),
						array( 'title' => 'Bathroom Cabinets & Vanities', 'page' => 'bathroom-cabinets-vanities' ),
						array( 'title' => 'Cabinet Styles', 'archive' => 'cabinet_collection' ),
					),
				),
				array( 'title' => 'Countertops', 'page' => 'countertops' ),
				array(
					'title' => 'Custom Spaces',
					'page'  => 'custom-spaces',
					'children' => array(
						array( 'title' => 'Closets', 'page' => 'closets' ),
						array( 'title' => 'Laundry & Pantry', 'page' => 'laundry-pantry' ),
						array( 'title' => 'Home Office', 'page' => 'home-office' ),
					),
				),
				array( 'title' => 'Portfolio', 'archive' => 'project' ),
				array( 'title' => 'Blog', 'page' => 'blog' ),
				array( 'title' => 'About', 'page' => 'about' ),
				array( 'title' => 'Contact', 'page' => 'contact' ),
			),
		),
		'footer-navigation'  => array(
			'label'    => 'Footer Navigation',
			'location' => 'footer',
			'items'    => array(
				array( 'title' => 'About', 'page' => 'about' ),
				array( 'title' => 'Contact', 'page' => 'contact' ),
				array( 'title' => 'Blog', 'page' => 'blog' ),
				array( 'title' => 'Portfolio', 'archive' => 'project' ),
			),
		),
	);

	foreach ( $menu_specs as $menu_slug => $spec ) {
		if ( zeus_seed_registry_has( 'menus', $menu_slug ) ) {
			continue;
		}

		// If a menu with this name already exists, it's either ours from
		// before the registry existed or an owner-managed menu with the
		// same name — either way, don't touch its items. Just record the
		// theme-location assignment and move on, matching the "existing
		// content is never modified" rule used everywhere else here.
		$menu = wp_get_nav_menu_object( $spec['label'] );
		if ( $menu ) {
			$locations                     = get_theme_mod( 'nav_menu_locations', array() );
			if ( empty( $locations[ $spec['location'] ] ) ) {
				$locations[ $spec['location'] ] = $menu->term_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			}
			zeus_seed_registry_mark( 'menus', $menu_slug );
			continue;
		}

		$menu_id = wp_create_nav_menu( $spec['label'] );
		if ( is_wp_error( $menu_id ) ) {
			continue;
		}

		foreach ( $spec['items'] as $item ) {
			$parent_item_id = zeus_seed_add_menu_item( $menu_id, $item, 0 );
			if ( $parent_item_id && ! empty( $item['children'] ) ) {
				foreach ( $item['children'] as $child ) {
					zeus_seed_add_menu_item( $menu_id, $child, $parent_item_id );
				}
			}
		}

		$locations                          = get_theme_mod( 'nav_menu_locations', array() );
		$locations[ $spec['location'] ]      = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );

		zeus_seed_registry_mark( 'menus', $menu_slug );
		$results[] = $spec['label'];
	}

	return $results;
}

function zeus_seed_add_menu_item( $menu_id, $item, $parent_item_id ) {
	$args = array(
		'menu-item-title'     => $item['title'],
		'menu-item-status'    => 'publish',
		'menu-item-parent-id' => $parent_item_id,
	);

	if ( ! empty( $item['page'] ) ) {
		$page = zeus_get_post_by_slug( $item['page'], 'page' );
		if ( ! $page ) {
			return 0;
		}
		$args['menu-item-object-id'] = $page->ID;
		$args['menu-item-object']    = 'page';
		$args['menu-item-type']      = 'post_type';
	} elseif ( ! empty( $item['archive'] ) ) {
		$url = get_post_type_archive_link( $item['archive'] );
		if ( ! $url ) {
			return 0;
		}
		$args['menu-item-url']  = $url;
		$args['menu-item-type'] = 'custom';
	} else {
		return 0;
	}

	$item_id = wp_update_nav_menu_item( $menu_id, 0, $args );
	return is_wp_error( $item_id ) ? 0 : $item_id;
}

/**
 * Runs every seed routine in the correct order. This is the single
 * entry point both the WP-CLI command and the admin Tools page call.
 */
function zeus_seed_all() {
	return array(
		'terms'       => zeus_seed_terms(),
		'collections' => zeus_seed_collections(),
		'pages'       => zeus_seed_pages(),
		'menus'       => zeus_seed_menus(),
	);
}
