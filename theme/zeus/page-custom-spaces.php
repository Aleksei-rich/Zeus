<?php
/**
 * Custom Spaces hub (/custom-spaces/) -- RC4C.
 * Auto-selected by WordPress's page-{slug}.php template hierarchy for
 * the "custom-spaces" page (ID 17); no post-meta template assignment
 * needed. Routes to the three child service pages (Closets, Laundry &
 * Pantry, Home Office).
 *
 * Positioning: purely custom cabinetry/built-ins -- no in-stock
 * messaging on this page or its children, unlike Kitchen/Bathroom. No
 * fabricated reviews, stats, certifications, warranty/licensing claims,
 * guaranteed lead times, or completed-project misattribution -- see
 * docs/PROJECT-SPEC.md. No independently-verified real ZEUS installation
 * photos exist for closets/laundry/pantry/home office in the approved
 * media library, so this page (and its three children) deliberately has
 * no "Real ZEUS Work" trust section rather than mislabeling generated or
 * dealer-lifestyle imagery as a completed project.
 */
get_header();
zeus_render_breadcrumbs();

$zeus_page_closets  = zeus_get_post_by_slug( 'closets', 'page' );
$zeus_page_laundry  = zeus_get_post_by_slug( 'laundry-pantry', 'page' );
$zeus_page_office   = zeus_get_post_by_slug( 'home-office', 'page' );
$zeus_page_cabinets = zeus_get_post_by_slug( 'cabinets', 'page' );

// Hero: Custom Closet -- generated category/lifestyle visual (RC3B),
// never presented as a completed ZEUS project (see
// docs/ASSET-PROVENANCE.csv). Chosen for the hub specifically because
// it gives the broadest, strongest "built-in cabinetry" impression of
// the three approved space images.
$zeus_hero_image_id = 154;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Custom Cabinetry for Your Home', 'zeus' ); ?>">
	<div class="zeus-hero__media">
		<?php if ( $zeus_hero_image_id ) : ?>
			<?php
			echo wp_get_attachment_image(
				$zeus_hero_image_id,
				'zeus-hero',
				false,
				array(
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'class'         => 'zeus-hero__img',
					'alt'           => __( 'Custom walk-in closet with built-in wood storage and a central island', 'zeus' ),
				)
			);
			?>
		<?php endif; ?>
		<div class="zeus-hero__scrim" aria-hidden="true"></div>
	</div>
	<div class="zeus-container">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Custom Spaces', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Custom Cabinetry for Your Home', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'Built-ins and storage designed around the way you use the room — from closets and laundry areas to pantries and home offices.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Explore Custom Spaces', 'zeus' ), 'url' => '#zeus-spaces', 'variant' => 'secondary', 'on_dark' => true ) ); ?>
			</div>
		</div>
	</div>
</section>

<!-- 2. Three custom space paths -->
<?php
zeus_section_start(
	array(
		'id'      => 'zeus-spaces',
		'eyebrow' => __( 'Where We Build', 'zeus' ),
		'heading' => __( 'Three Custom Space Paths', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--3">
		<?php
		get_template_part( 'components/card-service', null, array(
			'title'    => __( 'Custom Closets', 'zeus' ),
			'desc'     => __( 'Built-in storage designed around your wardrobe, room dimensions, and daily use.', 'zeus' ),
			'url'      => $zeus_page_closets ? get_permalink( $zeus_page_closets ) : home_url( '/custom-spaces/closets/' ),
			'image_id' => 154,
		) );
		get_template_part( 'components/card-service', null, array(
			'title'    => __( 'Laundry & Pantry', 'zeus' ),
			'desc'     => __( 'Cabinetry that turns utility and pantry areas into organized, functional storage.', 'zeus' ),
			'url'      => $zeus_page_laundry ? get_permalink( $zeus_page_laundry ) : home_url( '/custom-spaces/laundry-pantry/' ),
			'image_id' => 155,
		) );
		get_template_part( 'components/card-service', null, array(
			'title'    => __( 'Home Office', 'zeus' ),
			'desc'     => __( 'Desks, storage, and built-ins designed for a focused, integrated workspace.', 'zeus' ),
			'url'      => $zeus_page_office ? get_permalink( $zeus_page_office ) : home_url( '/custom-spaces/home-office/' ),
			'image_id' => 120,
		) );
		?>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. Why custom -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Our Approach', 'zeus' ),
		'heading' => __( 'When Standard Cabinet Sizes Are Not Enough', 'zeus' ),
		'intro'   => __( 'Some rooms don\'t fit a standard layout — an unusual wall length, an awkward niche, or storage that needs to reach the ceiling. We design and build cabinetry for the room in front of us, not a catalog size.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--3">
		<div><h3><?php esc_html_e( 'Room-Specific Design', 'zeus' ); ?></h3><p><?php esc_html_e( 'Unusual wall lengths, niches, and ceiling-height storage planned around the actual room.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'True Built-Ins', 'zeus' ); ?></h3><p><?php esc_html_e( 'Cabinetry and, where useful, integrated work surfaces built into the space rather than placed in it.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Organized for How You Live', 'zeus' ); ?></h3><p><?php esc_html_e( 'Storage planned around what actually goes in it, not a generic layout.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. Process -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'Process', 'zeus' ),
		'heading' => __( 'How a Custom Space Comes Together', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Discuss the room, storage needs, and goals.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Measure & Plan', 'zeus' ); ?></h3><p><?php esc_html_e( 'Exact dimensions and layout options for the space.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Design & Confirm', 'zeus' ); ?></h3><p><?php esc_html_e( 'Finalize the cabinetry design, finish, and details before fabrication.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Delivery & Installation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Professional delivery and installation coordinated by ZEUS.', 'zeus' ); ?></p></div>
	</div>
	<p style="margin-top: var(--wp--preset--spacing--3);">
		<?php
		printf(
			/* translators: %s: link to the Cabinets hub page */
			esc_html__( 'Custom cabinetry can also be finished to coordinate with your %s.', 'zeus' ),
			'<a href="' . esc_url( $zeus_page_cabinets ? get_permalink( $zeus_page_cabinets ) : home_url( '/cabinets/' ) ) . '">' . esc_html__( 'kitchen or bathroom cabinets', 'zeus' ) . '</a>'
		);
		?>
	</p>
<?php zeus_section_end(); ?>

<!-- 5. Service area -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Service Area', 'zeus' ),
		'heading' => __( 'Serving Orlando & Central Florida', 'zeus' ),
	)
);
?>
	<p>
		<?php
		esc_html_e(
			'ZEUS builds custom cabinetry for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.',
			'zeus'
		);
		?>
	</p>
	<p class="zeus-service-area__note">
		<?php esc_html_e( "Not sure if you're in our service area?", 'zeus' ); ?>
		<a href="<?php echo esc_url( zeus_phone_number_href() ); ?>"><?php esc_html_e( 'Call or text us.', 'zeus' ); ?></a>
	</p>
<?php zeus_section_end(); ?>

<!-- 6. FAQ -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'FAQ', 'zeus' ),
		'heading' => __( 'Custom Spaces Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'What types of custom cabinetry does ZEUS offer?', 'zeus' ),
		'a' => __( 'Custom closets, laundry room and pantry cabinetry, and home office built-ins — anywhere a standard cabinet size doesn\'t fit the space well.', 'zeus' ),
	),
	array(
		'q' => __( 'Do you design custom closets?', 'zeus' ),
		'a' => __( 'Yes, both walk-in and reach-in closets, planned around your wardrobe and the room\'s dimensions.', 'zeus' ),
	),
	array(
		'q' => __( 'Can you build cabinetry for laundry rooms and pantries?', 'zeus' ),
		'a' => __( 'Yes — cabinetry planned around your appliances, supplies, and everyday routine.', 'zeus' ),
	),
	array(
		'q' => __( 'Can ZEUS design a built-in home office?', 'zeus' ),
		'a' => __( 'Yes, including built-in desks, shelving, and closed storage designed around how you work.', 'zeus' ),
	),
	array(
		'q' => __( 'Do you work with unusual room dimensions?', 'zeus' ),
		'a' => __( 'That\'s the main reason to choose custom cabinetry — it\'s designed for the room you actually have, not a standard size.', 'zeus' ),
	),
	array(
		'q' => __( 'What areas around Orlando do you serve?', 'zeus' ),
		'a' => __( 'Orlando and nearby Central Florida communities, including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona. Call or text us if you\'re not sure your area is covered.', 'zeus' ),
	),
);
?>
	<div class="zeus-faq zeus-container--narrow" style="padding:0;">
		<?php foreach ( $zeus_faqs as $zeus_faq ) : ?>
			<details class="zeus-faq__item">
				<summary><?php echo esc_html( $zeus_faq['q'] ); ?></summary>
				<p><?php echo esc_html( $zeus_faq['a'] ); ?></p>
			</details>
		<?php endforeach; ?>
	</div>
<?php zeus_section_end(); ?>

<!-- 7. Final CTA / consultation form -->
<?php
zeus_section_start(
	array(
		'variant'   => 'compact',
		'eyebrow'   => __( 'Get Started', 'zeus' ),
		'heading'   => __( 'Have a Space That Needs a Better Solution?', 'zeus' ),
		'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
