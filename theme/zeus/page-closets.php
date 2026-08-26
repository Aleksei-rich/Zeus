<?php
/**
 * Custom Closets service page (/custom-spaces/closets/) -- RC4C.
 * Auto-selected by WordPress's page-{slug}.php template hierarchy for
 * the "closets" page (ID 68); no post-meta template assignment needed.
 * Purely custom cabinetry positioning -- no in-stock messaging. No
 * independently-verified real ZEUS closet installation photo exists in
 * the approved media library, so this page has no "Real ZEUS Work"
 * section (see docs/ASSET-PROVENANCE.csv) -- attachment 154 is a
 * generated category/lifestyle visual and must never be presented as a
 * completed ZEUS project.
 */
get_header();
zeus_render_breadcrumbs();

$zeus_page_custom_spaces = zeus_get_post_by_slug( 'custom-spaces', 'page' );

$zeus_hero_image_id = 154;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Custom Closets in Orlando', 'zeus' ); ?>">
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
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Custom Closets', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Custom Closets in Orlando', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'Built-in closet storage designed around your space, wardrobe, and daily routine.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Explore Custom Spaces', 'zeus' ), 'url' => $zeus_page_custom_spaces ? get_permalink( $zeus_page_custom_spaces ) : home_url( '/custom-spaces/' ), 'variant' => 'secondary', 'on_dark' => true ) ); ?>
			</div>
		</div>
	</div>
</section>

<!-- 2. Functional needs -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Designed Around You', 'zeus' ),
		'heading' => __( 'Storage Designed Around Your Wardrobe', 'zeus' ),
		'intro'   => __( 'A closet works when the layout matches what actually goes in it — hanging space, drawers, shelving, shoes, and accessories, planned to make the most of every wall, including the awkward corners.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--3">
		<div><h3><?php esc_html_e( 'Hanging & Folded Storage', 'zeus' ); ?></h3><p><?php esc_html_e( 'A mix of hanging rods, drawers, and shelves sized to your wardrobe.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Shoes & Accessories', 'zeus' ); ?></h3><p><?php esc_html_e( 'Dedicated storage for shoes, bags, and accessories instead of a single catch-all shelf.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Vertical & Corner Space', 'zeus' ); ?></h3><p><?php esc_html_e( 'Tall storage and awkward corners used, not left empty.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. Design approach -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Design Approach', 'zeus' ),
		'heading' => __( 'Planned Around the Room, Not a Kit', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<div>
			<?php echo wp_get_attachment_image( 154, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;', 'alt' => 'Custom closet island with drawer storage and integrated lighting' ) ); ?>
		</div>
		<div>
			<p><?php esc_html_e( 'We start with the room itself: available wall space, room dimensions, and how you move through the space. From there we plan storage priorities — what needs to be visible and reachable versus what can be tucked away — for a clean, integrated look rather than a stack of stock units.', 'zeus' ); ?></p>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. Types of spaces -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Closet Types', 'zeus' ),
		'heading' => __( 'Types of Closet Spaces We Build', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<div><h3><?php esc_html_e( 'Walk-In Closets', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Reach-In Closets', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Dressing Areas', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Bedroom Built-In Storage', 'zeus' ); ?></h3></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 5. Process -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'Process', 'zeus' ),
		'heading' => __( 'From Consultation to Installation', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Discuss your wardrobe, storage priorities, and the room.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Measure', 'zeus' ); ?></h3><p><?php esc_html_e( 'Exact room dimensions and available wall space.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Design', 'zeus' ); ?></h3><p><?php esc_html_e( 'A layout planned around how you actually use the closet.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Installation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Professional delivery and installation coordinated by ZEUS.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 6. Service area -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Service Area', 'zeus' ),
		'heading' => __( 'Custom Closets Across Orlando & Central Florida', 'zeus' ),
	)
);
?>
	<p>
		<?php
		esc_html_e(
			'ZEUS builds custom closets for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.',
			'zeus'
		);
		?>
	</p>
	<p class="zeus-service-area__note">
		<?php esc_html_e( "Not sure if you're in our service area?", 'zeus' ); ?>
		<a href="<?php echo esc_url( zeus_phone_number_href() ); ?>"><?php esc_html_e( 'Call or text us.', 'zeus' ); ?></a>
	</p>
<?php zeus_section_end(); ?>

<!-- 7. FAQ -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'FAQ', 'zeus' ),
		'heading' => __( 'Custom Closet Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'Do you design closets for unusual dimensions?', 'zeus' ),
		'a' => __( 'Yes — custom closet cabinetry is built for the room you actually have, including unusual shapes and dimensions.', 'zeus' ),
	),
	array(
		'q' => __( 'Can a closet include drawers and shelving?', 'zeus' ),
		'a' => __( 'Yes, a mix of hanging rods, drawers, and shelving is planned around your wardrobe.', 'zeus' ),
	),
	array(
		'q' => __( 'Do you build walk-in and reach-in closets?', 'zeus' ),
		'a' => __( 'Yes, both — the approach depends on the room and how you want to use it.', 'zeus' ),
	),
	array(
		'q' => __( 'Can cabinetry go to the ceiling?', 'zeus' ),
		'a' => __( 'Yes, ceiling-height storage is an option where the room allows for it.', 'zeus' ),
	),
	array(
		'q' => __( 'How does the design process start?', 'zeus' ),
		'a' => __( 'With a free consultation to discuss your space, wardrobe, and storage priorities.', 'zeus' ),
	),
	array(
		'q' => __( 'What areas do you serve?', 'zeus' ),
		'a' => __( 'Orlando and nearby Central Florida communities, including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.', 'zeus' ),
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

<!-- 8. Final CTA / consultation form -->
<?php
zeus_section_start(
	array(
		'variant'   => 'compact',
		'eyebrow'   => __( 'Get Started', 'zeus' ),
		'heading'   => __( 'Ready to Organize Your Closet?', 'zeus' ),
		'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
