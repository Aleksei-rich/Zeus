<?php
/**
 * Seeds the standard WordPress Pages this project's IA requires (see
 * docs/SITE-ARCHITECTURE.md URL map). Idempotent. Content here is
 * either genuinely factual/generic (e.g. general material properties)
 * or explicitly marked as a development placeholder — never fabricated
 * business claims (stats, certifications, years in business, awards).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_seed_pages() {
	if ( get_option( 'zeus_pages_seeded' ) ) {
		return;
	}

	$placeholder = '<p><em>' . esc_html__( '[Development placeholder: expand with real photography, specifics, and ZEUS-authored copy once available.]', 'zeus' ) . '</em></p>';

	$pages = array(
		'cabinets'                    => array(
			'title'   => 'Cabinets',
			'content' => '<p>ZEUS designs and builds custom kitchen and bathroom cabinetry for homes across Orlando and Central Florida.</p>' . $placeholder,
			'parent'  => 0,
		),
		'kitchen-cabinets'             => array(
			'title'   => 'Kitchen Cabinets',
			'content' => '<p>Custom kitchen cabinets built around how your kitchen is actually used — storage, layout, and finish all planned together rather than picked from a generic catalog.</p>' . $placeholder,
			'parent'  => 'cabinets',
		),
		'bathroom-cabinets-vanities'   => array(
			'title'   => 'Bathroom Cabinets & Vanities',
			'content' => '<p>Custom bathroom cabinetry and vanities sized and finished for your space, from a single vanity to a full primary-bath remodel.</p>' . $placeholder,
			'parent'  => 'cabinets',
		),
		'countertops'                  => array(
			'title'   => 'Countertops',
			'content' => '<p>ZEUS works with quartz, granite, porcelain, and marble countertops — the right material depends on how a kitchen or bathroom is actually used, not just how it looks on day one.</p>' . $placeholder,
			'parent'  => 0,
		),
		'quartz'                        => array(
			'title'   => 'Quartz Countertops',
			'content' => '<p>Quartz is an engineered stone — quartz mineral bound with resin — which makes it non-porous and highly consistent in pattern and color from slab to slab. It resists staining and doesn\'t require periodic sealing, which makes it a common choice for busy kitchens.</p>' . $placeholder,
			'parent'  => 'countertops',
		),
		'granite'                       => array(
			'title'   => 'Granite Countertops',
			'content' => '<p>Granite is a natural stone, so each slab is unique in pattern and veining. It\'s heat- and scratch-resistant and, like most natural stone, benefits from periodic sealing to stay stain-resistant over time.</p>' . $placeholder,
			'parent'  => 'countertops',
		),
		'porcelain'                     => array(
			'title'   => 'Porcelain Countertops',
			'content' => '<p>Porcelain slab is a manufactured material fired at very high temperatures, making it dense, non-porous, and highly resistant to heat, scratches, and UV fading — a common choice for outdoor kitchens as well as indoor use.</p>' . $placeholder,
			'parent'  => 'countertops',
		),
		'marble'                         => array(
			'title'   => 'Marble Countertops',
			'content' => '<p>Marble is a natural stone prized for its veining and classic look. It\'s softer and more porous than granite or quartz, so it takes more care around acidic foods and staining — many homeowners choose it for bathrooms or lower-traffic surfaces for that reason.</p>' . $placeholder,
			'parent'  => 'countertops',
		),
		'custom-spaces'                  => array(
			'title'   => 'Custom Spaces',
			'content' => '<p>Beyond kitchens and bathrooms, ZEUS builds custom cabinetry for closets, laundry and pantry rooms, and home offices.</p>' . $placeholder,
			'parent'  => 0,
		),
		'closets'                         => array(
			'title'   => 'Custom Closets',
			'content' => '<p>Custom closet systems built to fit your space and how you actually store things — not a one-size-fits-all kit.</p>' . $placeholder,
			'parent'  => 'custom-spaces',
		),
		'laundry-pantry'                  => array(
			'title'   => 'Laundry & Pantry',
			'content' => '<p>Custom cabinetry for laundry rooms and pantries — built-in storage planned around your appliances and everyday routine.</p>' . $placeholder,
			'parent'  => 'custom-spaces',
		),
		'home-office'                     => array(
			'title'   => 'Home Office',
			'content' => '<p>Custom home office cabinetry and built-ins for a dedicated, organized workspace.</p>' . $placeholder,
			'parent'  => 'custom-spaces',
		),
		'about'                            => array(
			'title'   => 'About',
			'content' => '<p>ZEUS Cabinets & Countertops designs and builds custom cabinetry and countertops for homeowners across Orlando and Central Florida.</p>' . $placeholder,
			'parent'  => 0,
		),
		'contact'                          => array(
			'title'   => 'Contact',
			'content' => '<p>ZEUS is a service-area business serving Orlando, Windermere, Winter Garden, Horizon West, Clermont, and Dr. Phillips. Consultations are by appointment — there is no public showroom to walk into.</p>' . $placeholder,
			'parent'  => 0,
		),
		'consultation'                     => array(
			'title'   => 'Request Free Consultation',
			'content' => '',
			'parent'  => 0,
			'template' => 'template-consultation.php',
		),
		'thank-you'                        => array(
			'title'   => 'Thank You',
			'content' => '<p>' . esc_html__( 'Thank you — your request has been received. A member of the ZEUS team will be in touch to schedule your free consultation.', 'zeus' ) . '</p>',
			'parent'  => 0,
			'noindex' => true,
		),
		'blog'                              => array(
			'title'   => 'Blog',
			'content' => '',
			'parent'  => 0,
		),
	);

	$slug_to_id = array();

	// Two passes so child pages can resolve their parent's new post ID.
	foreach ( $pages as $slug => $data ) {
		$existing = get_page_by_path( $slug );
		$slug_to_id[ $slug ] = $existing ? $existing->ID : 0;
	}

	foreach ( $pages as $slug => $data ) {
		if ( $slug_to_id[ $slug ] ) {
			continue;
		}
		$parent_id = 0;
		if ( is_string( $data['parent'] ) ) {
			$parent_id = $slug_to_id[ $data['parent'] ] ?? 0;
		}

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
	}

	if ( ! empty( $slug_to_id['blog'] ) ) {
		update_option( 'page_for_posts', $slug_to_id['blog'] );
		update_option( 'show_on_front', 'page' );
		$front = get_page_by_path( 'home' );
		// front-page.php is used automatically once show_on_front=page and
		// page_on_front is set; we don't need a literal "home" page since
		// front-page.php is the template for the site root regardless of
		// page_on_front when no static page is required by WP for that slot
		// — but WP does require a page_on_front ID, so create a minimal one.
		if ( ! $front ) {
			$home_id = wp_insert_post(
				array(
					'post_type'   => 'page',
					'post_title'  => 'Home',
					'post_name'   => 'home',
					'post_status' => 'publish',
				)
			);
			if ( $home_id && ! is_wp_error( $home_id ) ) {
				update_option( 'page_on_front', $home_id );
			}
		} else {
			update_option( 'page_on_front', $front->ID );
		}
	}

	update_option( 'zeus_pages_seeded', 1 );
}
add_action( 'init', 'zeus_seed_pages', 30 );
