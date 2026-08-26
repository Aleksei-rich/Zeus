<?php
/**
 * Granite Countertops page (/countertops/granite/) -- RC4D.
 * Auto-selected by WordPress's page-{slug}.php template hierarchy for
 * the "granite" page (ID 14); no post-meta template assignment needed.
 * No absolute maintenance claims ("granite never stains") -- sealing
 * language is deliberately conditional ("depending on the stone,
 * fabrication and sealer") per docs/DECISIONS.md ("RC4D"). Attachment
 * 157 is a generated category/lifestyle visual (see
 * docs/ASSET-PROVENANCE.csv) and must never be presented as a
 * completed ZEUS project. No independently-verified real ZEUS
 * granite-specific installation photo exists, so this page has no
 * "Real ZEUS Work" section.
 */
get_header();
zeus_render_breadcrumbs();

$zeus_page_kitchen = zeus_get_post_by_slug( 'kitchen-cabinets', 'page' );
$zeus_page_bath    = zeus_get_post_by_slug( 'bathroom-cabinets-vanities', 'page' );
$zeus_page_ct      = zeus_get_post_by_slug( 'countertops', 'page' );

$zeus_hero_image_id = 157;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Granite Countertops in Orlando', 'zeus' ); ?>">
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
					'alt'           => __( 'Kitchen with natural granite countertop featuring gray, beige and dark mineral pattern', 'zeus' ),
				)
			);
			?>
		<?php endif; ?>
		<div class="zeus-hero__scrim" aria-hidden="true"></div>
	</div>
	<div class="zeus-container">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Granite Countertops', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Granite Countertops in Orlando', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'Natural stone with unique movement, mineral pattern and color variation from slab to slab.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Compare Materials', 'zeus' ), 'url' => $zeus_page_ct ? get_permalink( $zeus_page_ct ) . '#zeus-compare' : home_url( '/countertops/#zeus-compare' ), 'variant' => 'secondary', 'on_dark' => true ) ); ?>
			</div>
		</div>
	</div>
</section>

<!-- 2. Why granite -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Why Granite', 'zeus' ),
		'heading' => __( 'Why Homeowners Choose Granite', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<div><h3><?php esc_html_e( 'Genuine Natural Stone', 'zeus' ); ?></h3><p><?php esc_html_e( 'Quarried, not manufactured — real mineral character throughout.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Every Slab Is Different', 'zeus' ); ?></h3><p><?php esc_html_e( 'No two slabs are exactly alike in pattern or color.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Depth & Mineral Movement', 'zeus' ); ?></h3><p><?php esc_html_e( 'Genuine dimensional variation you don\'t get from a printed surface.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Traditional or Modern', 'zeus' ); ?></h3><p><?php esc_html_e( 'Works in both, depending on the slab and design.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. The slab is part of the design -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Selecting Granite', 'zeus' ),
		'heading' => __( 'The Slab Is Part of the Design', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<div>
			<?php echo wp_get_attachment_image( 157, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;', 'alt' => 'Close view of granite countertop mineral movement and variation' ) ); ?>
		</div>
		<div>
			<p><?php esc_html_e( 'With natural stone, the individual slab is part of the design, not just the material. The exact veining and mineral movement should be considered during selection — what you see at the slab is what ends up on your countertop, so we recommend viewing the actual slab together before committing, especially for a large island.', 'zeus' ); ?></p>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. Care -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Maintenance', 'zeus' ),
		'heading' => __( 'Caring for Granite', 'zeus' ),
	)
);
?>
	<p><?php esc_html_e( 'Some granite may benefit from periodic sealing, depending on the specific stone, fabrication, and sealer used. Follow the care recommendations for your selected slab, and wipe up spills — especially oil and acidic liquids — promptly to help keep the surface looking its best.', 'zeus' ); ?></p>
<?php zeus_section_end(); ?>

<!-- 5. Applications -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Applications', 'zeus' ),
		'heading' => __( 'Where Granite Works', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<div><h3><?php esc_html_e( 'Kitchen', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Bathroom Vanity', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Island', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Other Cabinetry Surfaces', 'zeus' ); ?></h3></div>
	</div>
	<p style="margin-top: var(--wp--preset--spacing--3);">
		<?php
		printf(
			/* translators: 1: link to Kitchen Cabinets, 2: link to Bathroom Cabinets & Vanities */
			wp_kses_post( __( 'We\'ll pair granite selection with your %1$s or %2$s during your consultation.', 'zeus' ) ),
			'<a href="' . esc_url( $zeus_page_kitchen ? get_permalink( $zeus_page_kitchen ) : home_url( '/cabinets/kitchen-cabinets/' ) ) . '">' . esc_html__( 'kitchen cabinets', 'zeus' ) . '</a>',
			'<a href="' . esc_url( $zeus_page_bath ? get_permalink( $zeus_page_bath ) : home_url( '/cabinets/bathroom-cabinets-vanities/' ) ) . '">' . esc_html__( 'bathroom vanities', 'zeus' ) . '</a>'
		);
		?>
	</p>
<?php zeus_section_end(); ?>

<!-- 6. Process -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'Process', 'zeus' ),
		'heading' => __( 'From Slab Selection to Installation', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Discuss your project and cabinetry pairing.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Slab Selection', 'zeus' ); ?></h3><p><?php esc_html_e( 'Review the actual slab, not just a sample chip.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Final Measurement / Template', 'zeus' ); ?></h3><p><?php esc_html_e( 'Precise measurements taken once cabinetry is in place.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Fabrication & Installation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Professional fabrication and installation coordinated by ZEUS.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 7. Service area -->
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
			'ZEUS installs granite countertops for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.',
			'zeus'
		);
		?>
	</p>
	<p class="zeus-service-area__note">
		<?php esc_html_e( "Not sure if you're in our service area?", 'zeus' ); ?>
		<a href="<?php echo esc_url( zeus_phone_number_href() ); ?>"><?php esc_html_e( 'Call or text us.', 'zeus' ); ?></a>
	</p>
<?php zeus_section_end(); ?>

<!-- 8. FAQ -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'FAQ', 'zeus' ),
		'heading' => __( 'Granite Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'Is every granite slab different?', 'zeus' ),
		'a' => __( 'Yes — as natural stone, each granite slab has its own pattern, color, and mineral flecking.', 'zeus' ),
	),
	array(
		'q' => __( 'Does granite need sealing?', 'zeus' ),
		'a' => __( 'Many granite countertops benefit from sealing at installation and periodic resealing after that, though how often depends on the specific stone.', 'zeus' ),
	),
	array(
		'q' => __( 'How is granite different from quartz?', 'zeus' ),
		'a' => __( 'Granite is natural stone with slab-to-slab variation; quartz is engineered for more predictable, consistent pattern and color.', 'zeus' ),
	),
	array(
		'q' => __( 'Can I choose the exact slab?', 'zeus' ),
		'a' => __( 'Yes — we recommend viewing the actual slab before committing, especially for a large island.', 'zeus' ),
	),
	array(
		'q' => __( 'Can granite be used in bathrooms?', 'zeus' ),
		'a' => __( 'Yes, granite is a common choice for bathroom vanities as well as kitchens.', 'zeus' ),
	),
	array(
		'q' => __( 'Does ZEUS coordinate cabinets and countertops?', 'zeus' ),
		'a' => __( 'Yes — cabinetry and countertop material are planned together, not chosen separately.', 'zeus' ),
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

<!-- 9. Final CTA / consultation form -->
<?php
zeus_section_start(
	array(
		'variant'   => 'compact',
		'eyebrow'   => __( 'Get Started', 'zeus' ),
		'heading'   => __( 'Considering Granite for Your Project?', 'zeus' ),
		'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
