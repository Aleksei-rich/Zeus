<?php
/**
 * Cabinet Style -> Color -> Gallery pages.
 *
 * Reusable routing + curated content for individual cabinet-color detail
 * pages (e.g. /cabinet-styles/brooklyn/white/), nested one level under
 * each cabinet_collection's own URL. See docs/CONTENT-MODEL.md ("Cabinet
 * Color Pages") and docs/DECISIONS.md (2026-09-18 entry, which supersedes
 * the earlier "finishes are not separate URLs" decision for whichever
 * collections have curated content here).
 *
 * A color page exists only when it has an entry in
 * zeus_get_cabinet_color_content_map() below -- that entry's presence
 * IS the publish flag, so an unmapped combination (e.g. any Shaker/Oslo/
 * Euro color right now) 404s instead of silently re-rendering the parent
 * collection page at a second URL. Adding a color or a whole new style
 * later means adding data here (copy + verified image IDs), never a new
 * template -- see theme/zeus/single-cabinet-color.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Curated content map: [ style_slug => [ color_slug => [...] ] ].
 *
 * Every image ID below is verified against docs/ASSET-PROVENANCE.csv
 * (cross-checked live against postmeta/attachment titles during this
 * feature's build) as a real, dealer-provided photo of Brooklyn in that
 * exact finish -- never another collection's imagery, never a stock or
 * generated substitute. Brooklyn is the only style populated here for
 * now; see docs/DECISIONS.md.
 */
function zeus_get_cabinet_color_content_map() {
	return array(
		'brooklyn' => array(
			'white'    => array(
				'seo_title'       => __( 'Brooklyn White Kitchen Cabinets | ZEUS Cabinets & Countertops', 'zeus-core' ),
				'seo_description' => __( 'See Brooklyn White cabinets in real kitchens, and how this bright, neutral finish pairs with countertops, hardware, and flooring.', 'zeus-core' ),
				'h1'              => __( 'Brooklyn White Kitchen Cabinets', 'zeus-core' ),
				'intro'           => array(
					__( "Brooklyn White is a clean, true white on the collection's full-overlay door -- the door fronts sit nearly flush across a run of cabinetry, so a white Brooklyn kitchen reads as one continuous, bright surface rather than a series of separate framed doors.", 'zeus-core' ),
					__( "White is a neutral backdrop, so it gives you the most flexibility everywhere else in the room. It works with light or dark countertops, and holds up next to both cooler stones (light quartz, gray-veined marble) and warmer ones. Matte black or brass hardware reads as a deliberate contrast; brushed nickel keeps the look softer. Flooring can run light, mid-tone, or dark without competing with the cabinetry.", 'zeus-core' ),
				),
				'hero_id'         => 116,
				'hero_alt'        => __( 'Brooklyn White kitchen cabinets', 'zeus-core' ),
				'gallery'         => array(
					array(
						'id'  => 116,
						'alt' => __( 'Brooklyn White kitchen cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 117,
						'alt' => __( 'Brooklyn White kitchen cabinets', 'zeus-core' ),
					),
				),
			),
			'pearl'    => array(
				'seo_title'       => __( 'Brooklyn Pearl Kitchen Cabinets | ZEUS Cabinets & Countertops', 'zeus-core' ),
				'seo_description' => __( 'See Brooklyn Pearl cabinets in a real kitchen, bathroom, and home office, and how this warm off-white finish pairs with countertops and hardware.', 'zeus-core' ),
				'h1'              => __( 'Brooklyn Pearl Kitchen Cabinets', 'zeus-core' ),
				'intro'           => array(
					__( "Brooklyn Pearl is a warm off-white -- softer and less stark than a pure white, with a faint warmth that keeps a room from feeling clinical. On Brooklyn's full-overlay door, that warmth reads consistently across a full run of cabinetry.", 'zeus-core' ),
					__( "Because Pearl leans warm rather than cool, it pairs naturally with warm-toned countertops (cream or beige quartz, warm granite) and warm wood flooring, and looks equally at home in a kitchen, a bathroom vanity, or a home-office built-in. Brass or brushed-gold hardware continues the warm tone; matte black gives it more contrast if you'd rather the room not read as fully monochromatic.", 'zeus-core' ),
				),
				'hero_id'         => 118,
				'hero_alt'        => __( 'Brooklyn Pearl kitchen cabinets', 'zeus-core' ),
				'gallery'         => array(
					array(
						'id'  => 118,
						'alt' => __( 'Brooklyn Pearl kitchen cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 119,
						'alt' => __( 'Brooklyn Pearl bathroom vanity cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 120,
						'alt' => __( 'Brooklyn Pearl home office cabinetry', 'zeus-core' ),
					),
				),
			),
			'fawn'     => array(
				'seo_title'       => __( 'Brooklyn Fawn Kitchen Cabinets | ZEUS Cabinets & Countertops', 'zeus-core' ),
				'seo_description' => __( 'See Brooklyn Fawn cabinets in a real kitchen and bathroom, and how this warm tan finish pairs with countertops, wood tones, and hardware.', 'zeus-core' ),
				'h1'              => __( 'Brooklyn Fawn Kitchen Cabinets', 'zeus-core' ),
				'intro'           => array(
					__( "Brooklyn Fawn is a warm, light tan -- earthier than White or Pearl, without moving into a strong contrast color. It suits a room meant to feel warm and grounded rather than crisp and bright.", 'zeus-core' ),
					__( "Fawn reads well next to natural materials: wood-tone flooring, warm quartz or granite with beige or gold veining, and stone or woven texture in a backsplash. Brushed brass or oil-rubbed bronze hardware keeps the warm palette consistent; black hardware stands out clearly against Fawn if you'd rather add contrast.", 'zeus-core' ),
				),
				'hero_id'         => 110,
				'hero_alt'        => __( 'Brooklyn Fawn kitchen cabinets', 'zeus-core' ),
				'gallery'         => array(
					array(
						'id'  => 110,
						'alt' => __( 'Brooklyn Fawn kitchen cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 111,
						'alt' => __( 'Brooklyn Fawn bathroom vanity cabinets', 'zeus-core' ),
					),
				),
			),
			'gray'     => array(
				'seo_title'       => __( 'Brooklyn Gray Kitchen & Bath Cabinets | ZEUS Cabinets & Countertops', 'zeus-core' ),
				'seo_description' => __( 'See Brooklyn Gray cabinets in a real bathroom and home office, and how this cool neutral finish pairs with countertops and flooring.', 'zeus-core' ),
				'h1'              => __( 'Brooklyn Gray Kitchen & Bath Cabinets', 'zeus-core' ),
				// No verified Brooklyn Gray *kitchen* photo currently exists (see
				// docs/ASSET-PROVENANCE.csv) -- H1/gallery deliberately say
				// "Kitchen & Bath" instead of "Kitchen" so the page never implies
				// a kitchen installation photo that doesn't exist. Revisit if a
				// kitchen photo is added later.
				'intro'           => array(
					__( "Brooklyn Gray is a cool, neutral gray -- calmer and less stark than white, without the strong statement of Slate or Midnight. It reads as a modern neutral that doesn't lean as warm or cold as Brooklyn's other finishes.", 'zeus-core' ),
					__( "Gray pairs comfortably with both warm and cool countertops, and shows especially well next to white or gray-veined quartz and marble. Warm wood flooring balances the cool tone of the cabinetry; black or brushed-nickel hardware both read cleanly against it. The photos below show Brooklyn Gray in a bathroom vanity and a home-office built-in -- the same finish is available for kitchen cabinetry, which we'll show you during your consultation.", 'zeus-core' ),
				),
				'hero_id'         => 113,
				'hero_alt'        => __( 'Brooklyn Gray bathroom vanity cabinets', 'zeus-core' ),
				'gallery'         => array(
					array(
						'id'  => 113,
						'alt' => __( 'Brooklyn Gray bathroom vanity cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 112,
						'alt' => __( 'Brooklyn Gray cabinetry in a home office', 'zeus-core' ),
					),
				),
			),
			'slate'    => array(
				'seo_title'       => __( 'Brooklyn Slate Kitchen Cabinets | ZEUS Cabinets & Countertops', 'zeus-core' ),
				'seo_description' => __( 'See Brooklyn Slate cabinets in a real kitchen, and how this deep charcoal finish pairs with countertops, lighting, and hardware.', 'zeus-core' ),
				'h1'              => __( 'Brooklyn Slate Kitchen Cabinets', 'zeus-core' ),
				'intro'           => array(
					__( "Brooklyn Slate is a deep charcoal -- a bolder, cooler-toned finish for a kitchen meant to carry more visual weight than a neutral color gives it. On Brooklyn's minimal-line door, a full run of Slate cabinetry reads as one strong, continuous dark surface.", 'zeus-core' ),
					__( "Because Slate is dark, it benefits from contrast elsewhere in the room: a lighter countertop (white or light-veined quartz or marble) keeps the space from feeling heavy, and brass or gold hardware stands out clearly against it. Good ambient and under-cabinet lighting matters more with a dark finish than a light one -- we'll talk through fixture placement during your consultation. Light or mid-tone wood flooring helps balance the darker cabinetry.", 'zeus-core' ),
				),
				'hero_id'         => 122,
				'hero_alt'        => __( 'Brooklyn Slate kitchen cabinets', 'zeus-core' ),
				'gallery'         => array(
					array(
						'id'  => 122,
						'alt' => __( 'Brooklyn Slate kitchen cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 121,
						'alt' => __( 'Brooklyn Slate kitchen cabinets', 'zeus-core' ),
					),
				),
			),
			'midnight' => array(
				'seo_title'       => __( 'Brooklyn Midnight Kitchen Cabinets | ZEUS Cabinets & Countertops', 'zeus-core' ),
				'seo_description' => __( 'See Brooklyn Midnight cabinets in a real kitchen and bathroom, and how this deep navy finish pairs with countertops and hardware.', 'zeus-core' ),
				'h1'              => __( 'Brooklyn Midnight Kitchen Cabinets', 'zeus-core' ),
				'intro'           => array(
					__( "Brooklyn Midnight is a deep navy blue -- the darkest, most saturated color in the collection, and a genuine color statement rather than a black or a neutral. It's a deliberate choice for a kitchen where the cabinetry is meant to be a focal point rather than a backdrop.", 'zeus-core' ),
					__( "Because Midnight is a true color rather than a neutral, it pairs especially well with warm metals: brass or gold hardware stands out clearly against the blue, and light countertops (white quartz, light marble) keep the room from feeling closed in. Light or mid-tone wood floors help balance the depth of the cabinetry.", 'zeus-core' ),
				),
				'hero_id'         => 114,
				'hero_alt'        => __( 'Brooklyn Midnight kitchen cabinets', 'zeus-core' ),
				'gallery'         => array(
					array(
						'id'  => 114,
						'alt' => __( 'Brooklyn Midnight kitchen cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 115,
						'alt' => __( 'Brooklyn Midnight bathroom vanity cabinets', 'zeus-core' ),
					),
				),
			),
		),
		'shaker' => array(
			'white'  => array(
				'seo_title'       => __( 'Shaker White Kitchen Cabinets | ZEUS Cabinets & Countertops', 'zeus-core' ),
				'seo_description' => __( 'Explore Shaker White cabinets in kitchen settings and see how this bright, versatile finish works with countertops, hardware, and flooring.', 'zeus-core' ),
				'h1'              => __( 'Shaker White Kitchen Cabinets', 'zeus-core' ),
				'intro'           => array(
					__( "Shaker White combines the collection's classic five-piece recessed-panel door with a bright, versatile finish. The wider Shaker frame remains clearly visible, giving the cabinetry more traditional definition than a Slim Shaker profile while still fitting transitional and modern rooms.", 'zeus-core' ),
					__( "White gives you broad flexibility for the rest of the room: light or dark countertops can both work, while black, brushed nickel, brass, or gold hardware each create a different level of contrast. Wood flooring and warm stone can soften the palette; cooler quartz and marble keep it crisp.", 'zeus-core' ),
				),
				'hero_id'         => 123,
				'hero_alt'        => __( 'Shaker White kitchen cabinets', 'zeus-core' ),
				'gallery_heading' => __( 'Shaker White Kitchens', 'zeus-core' ),
				'gallery'         => array(
					array(
						'id'  => 123,
						'alt' => __( 'Shaker White kitchen cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 124,
						'alt' => __( 'Shaker White kitchen cabinets', 'zeus-core' ),
					),
				),
			),
			'sand'   => array(
				'seo_title'       => __( 'Shaker Sand Kitchen Cabinets | ZEUS Cabinets & Countertops', 'zeus-core' ),
				'seo_description' => __( 'Explore Shaker Sand cabinets in kitchen settings and see how this warm neutral finish pairs with countertops, wood tones, and hardware.', 'zeus-core' ),
				'h1'              => __( 'Shaker Sand Kitchen Cabinets', 'zeus-core' ),
				'intro'           => array(
					__( "Shaker Sand brings a softer, warmer neutral to the collection's classic recessed-panel door. It keeps the familiar Shaker geometry while moving away from the sharper contrast of bright white.", 'zeus-core' ),
					__( "Sand works naturally with warm woods, cream or beige stone, and countertops with warmer veining. Brass and bronze continue the warmer direction, while matte black hardware adds a stronger graphic contrast.", 'zeus-core' ),
				),
				'hero_id'         => 125,
				'hero_alt'        => __( 'Shaker Sand kitchen cabinets', 'zeus-core' ),
				'gallery_heading' => __( 'Shaker Sand Kitchens', 'zeus-core' ),
				'gallery'         => array(
					array(
						'id'  => 125,
						'alt' => __( 'Shaker Sand kitchen cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 126,
						'alt' => __( 'Shaker Sand kitchen cabinets', 'zeus-core' ),
					),
				),
			),
			'kodiak' => array(
				'seo_title'       => __( 'Shaker Kodiak Kitchen & Bath Cabinets | ZEUS Cabinets & Countertops', 'zeus-core' ),
				'seo_description' => __( 'Explore Shaker Kodiak cabinets in kitchen and bathroom settings and see how this deeper, earth-toned finish pairs with lighter surfaces and hardware.', 'zeus-core' ),
				'h1'              => __( 'Shaker Kodiak Kitchen & Bath Cabinets', 'zeus-core' ),
				'intro'           => array(
					__( "Shaker Kodiak is one of the collection's deeper, earthier choices. The darker finish gives the classic five-piece door more visual weight and makes the recessed center panel and wider frame read more strongly across a run of cabinetry.", 'zeus-core' ),
					__( "Lighter countertops and backsplashes create useful contrast with Kodiak, while warm wood and natural stone can build a richer tonal palette. Brushed brass or warm metal hardware reinforces that depth; black hardware keeps the look more restrained.", 'zeus-core' ),
				),
				'hero_id'         => 127,
				'hero_alt'        => __( 'Shaker Kodiak kitchen cabinets', 'zeus-core' ),
				'gallery'         => array(
					array(
						'id'  => 127,
						'alt' => __( 'Shaker Kodiak kitchen cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 128,
						'alt' => __( 'Shaker Kodiak bathroom vanity cabinets', 'zeus-core' ),
					),
				),
			),
			'moss'   => array(
				'seo_title'       => __( 'Shaker Moss Kitchen & Bath Cabinets | ZEUS Cabinets & Countertops', 'zeus-core' ),
				'seo_description' => __( 'Explore Shaker Moss cabinets in kitchen and bathroom settings and see how this earthy green finish pairs with stone, wood, and warm metal hardware.', 'zeus-core' ),
				'h1'              => __( 'Shaker Moss Kitchen & Bath Cabinets', 'zeus-core' ),
				'intro'           => array(
					__( "Shaker Moss gives the traditional recessed-panel profile an earthy green direction. It is a stronger color choice than White or Sand, while still working comfortably with natural materials and warm interior palettes.", 'zeus-core' ),
					__( "Moss pairs especially well with wood accents, light stone, and countertops with warm or natural veining. Brass and gold hardware bring out the warmer side of the finish; black hardware creates a cleaner, more architectural contrast.", 'zeus-core' ),
				),
				'hero_id'         => 129,
				'hero_alt'        => __( 'Shaker Moss kitchen cabinets', 'zeus-core' ),
				'gallery'         => array(
					array(
						'id'  => 129,
						'alt' => __( 'Shaker Moss kitchen cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 130,
						'alt' => __( 'Shaker Moss bathroom vanity cabinets', 'zeus-core' ),
					),
				),
			),
		),
		'oslo' => array(
			'white'  => array(
				'seo_title'       => __( 'Oslo White Slim Shaker Cabinets | ZEUS Cabinets & Countertops', 'zeus-core' ),
				'seo_description' => __( 'Explore Oslo White Slim Shaker cabinets in kitchen and bathroom settings and see how this light finish supports a clean, modern framed look.', 'zeus-core' ),
				'h1'              => __( 'Oslo White Slim Shaker Cabinets', 'zeus-core' ),
				'intro'           => array(
					__( "Oslo White combines a light finish with the collection's narrow Slim Shaker frame. The recessed-panel geometry is still visible, but the slimmer rail gives the cabinetry a cleaner, more architectural line than traditional Shaker.", 'zeus-core' ),
					__( "White keeps that narrow-frame profile bright and flexible. It can pair with light or dark countertops, warm or cool stone, and hardware ranging from subtle brushed nickel to higher-contrast black, brass, or gold.", 'zeus-core' ),
				),
				'hero_id'         => 131,
				'hero_alt'        => __( 'Oslo White Slim Shaker kitchen cabinets', 'zeus-core' ),
				'gallery'         => array(
					array(
						'id'  => 131,
						'alt' => __( 'Oslo White Slim Shaker kitchen cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 132,
						'alt' => __( 'Oslo White Slim Shaker bathroom vanity cabinets', 'zeus-core' ),
					),
				),
			),
			'oak'    => array(
				'seo_title'       => __( 'Oslo Oak Slim Shaker Cabinets | ZEUS Cabinets & Countertops', 'zeus-core' ),
				'seo_description' => __( 'Explore Oslo Oak Slim Shaker cabinets in kitchen and bathroom settings and see how this natural wood finish creates a warm contemporary look.', 'zeus-core' ),
				'h1'              => __( 'Oslo Oak Slim Shaker Cabinets', 'zeus-core' ),
				'intro'           => array(
					__( "Oslo Oak adds a natural wood direction to the collection's narrow Slim Shaker profile. The combination keeps the door visually structured while bringing more warmth and material texture than a painted finish.", 'zeus-core' ),
					__( "Oak works naturally with light quartz, marble-look surfaces, and other understated stones that let the wood remain visible as a design element. Black hardware adds definition; brushed brass or softer metal finishes keep the palette warmer.", 'zeus-core' ),
				),
				'hero_id'         => 133,
				'hero_alt'        => __( 'Oslo Oak Slim Shaker kitchen cabinets', 'zeus-core' ),
				'gallery'         => array(
					array(
						'id'  => 133,
						'alt' => __( 'Oslo Oak Slim Shaker kitchen cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 134,
						'alt' => __( 'Oslo Oak Slim Shaker bathroom vanity cabinets', 'zeus-core' ),
					),
				),
			),
			'walnut' => array(
				'seo_title'       => __( 'OSLO Classic Walnut Slim Shaker Cabinets | ZEUS Cabinets & Countertops', 'zeus-core' ),
				'seo_description' => __( 'Explore OSLO Classic Walnut Slim Shaker cabinetry in kitchens, bathrooms, and built-ins, with a rich natural walnut finish and narrow framed profile.', 'zeus-core' ),
				'h1'              => __( 'OSLO Classic Walnut Slim Shaker Cabinets', 'zeus-core' ),
				'intro'           => array(
					__( "OSLO Classic Walnut combines a rich natural walnut finish with the collection's narrow Slim Shaker frame. The result is warmer and more architectural than a painted cabinet, while the slim rail keeps the overall composition streamlined.", 'zeus-core' ),
					__( "Walnut works especially well with lighter countertops and wall finishes that preserve contrast and let the wood grain remain a focal material. Brass or warm metal hardware can reinforce the richness of the wood; black hardware gives the cabinetry a sharper contemporary edge.", 'zeus-core' ),
				),
				'hero_id'         => 136,
				'hero_alt'        => __( 'OSLO Classic Walnut Slim Shaker kitchen cabinets', 'zeus-core' ),
				'gallery_heading' => __( 'OSLO Classic Walnut in Kitchens, Baths & Built-Ins', 'zeus-core' ),
				'gallery'         => array(
					array(
						'id'  => 136,
						'alt' => __( 'OSLO Classic Walnut Slim Shaker kitchen cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 137,
						'alt' => __( 'OSLO Classic Walnut Slim Shaker bathroom vanity cabinets', 'zeus-core' ),
					),
					array(
						'id'  => 138,
						'alt' => __( 'OSLO Classic Walnut Slim Shaker cabinetry in a home bar', 'zeus-core' ),
					),
				),
			),
		),
	);
}

/**
 * Looks up one color page's content. Returns null when unpublished
 * (no curated entry yet) -- callers must treat null as "this page does
 * not exist," not as an error to work around.
 */
function zeus_get_cabinet_color_content( $style_slug, $color_slug ) {
	$map = zeus_get_cabinet_color_content_map();
	return $map[ $style_slug ][ $color_slug ] ?? null;
}

function zeus_cabinet_color_url( $style_slug, $color_slug ) {
	return home_url( '/cabinet-styles/' . $style_slug . '/' . $color_slug . '/' );
}

/**
 * /cabinet-styles/{collection-slug}/{finish-slug}/ -- nests a color page
 * under the collection's own URL. `cabinet_collection` is the query var
 * WordPress already auto-registers for that post type's rewrite slug,
 * so this reuses the same singular-post query as the collection page
 * itself; theme/zeus/inc/cabinet-colors.php decides which template
 * renders it.
 */
function zeus_add_cabinet_color_rewrite_rule() {
	add_rewrite_rule(
		'^cabinet-styles/([^/]+)/([^/]+)/?$',
		'index.php?cabinet_collection=$matches[1]&zeus_cabinet_color=$matches[2]',
		'top'
	);
}
add_action( 'init', 'zeus_add_cabinet_color_rewrite_rule', 20 );

function zeus_register_cabinet_color_query_var( $vars ) {
	$vars[] = 'zeus_cabinet_color';
	return $vars;
}
add_filter( 'query_vars', 'zeus_register_cabinet_color_query_var' );

/**
 * Gate: only a curated, published color resolves. Anything else under
 * this same 2-segment URL shape (an unmapped color, or a whole style
 * that has not been reviewed/published yet, such as Euro finishes right
 * now) 404s cleanly instead of silently re-rendering the parent
 * collection page a second time at a different URL.
 */
function zeus_gate_cabinet_color_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	$color_slug = $query->get( 'zeus_cabinet_color' );
	if ( ! $color_slug ) {
		return;
	}
	$style_slug = $query->get( 'cabinet_collection' );
	if ( ! $style_slug || ! zeus_get_cabinet_color_content( $style_slug, $color_slug ) ) {
		$query->set_404();
		// WP::handle_404() sends the actual 404 status header, but it
		// bails out early ("if we've already issued a 404, bail") because
		// set_404() above already flips is_404() to true before
		// handle_404() ever runs -- without this, the response would
		// silently stay 200. Send it here instead, and disable core's
		// redirect_canonical(), which otherwise "corrects" this 404 by
		// 301-redirecting to the real collection post it matched (e.g.
		// /cabinet-styles/shaker/white/ -> /cabinet-styles/shaker/),
		// masking an unpublished/unmapped color as if it worked. Both
		// narrowly scoped to this gate, not sitewide 404 behavior.
		status_header( 404 );
		nocache_headers();
		add_filter( 'redirect_canonical', '__return_false' );
	}
}
add_action( 'pre_get_posts', 'zeus_gate_cabinet_color_query' );

/**
 * One-time rewrite flush when this rule set changes, instead of an
 * unconditional flush_rewrite_rules() on every request. Bump the
 * version string any time the rewrite pattern itself changes.
 */
function zeus_maybe_flush_cabinet_color_rewrite() {
	$version = '2026-09-18.1';
	if ( get_option( 'zeus_cabinet_color_rewrite_version' ) !== $version ) {
		flush_rewrite_rules();
		update_option( 'zeus_cabinet_color_rewrite_version', $version );
	}
}
add_action( 'init', 'zeus_maybe_flush_cabinet_color_rewrite', 30 );
