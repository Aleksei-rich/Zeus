<?php
/**
 * Marble Countertops page (/countertops/marble/) -- RC4D.
 * Auto-selected by WordPress's page-{slug}.php template hierarchy for
 * the "marble" page (ID 16); no post-meta template assignment needed.
 * Care/sensitivity language is deliberately transparent (etching,
 * staining, patina) rather than a negative sales section -- it's meant
 * to help a homeowner decide whether marble fits their expectations,
 * not to discourage or oversell -- see docs/DECISIONS.md ("RC4D").
 * Sealing is never claimed to prevent all etching/staining. Attachment
 * 159 is a generated category/lifestyle visual (see
 * docs/ASSET-PROVENANCE.csv) and must never be presented as a
 * completed ZEUS project. No independently-verified real ZEUS
 * marble-specific installation photo exists, so this page has no
 * "Real ZEUS Work" section.
 */
get_header();
zeus_render_breadcrumbs();

$zeus_page_kitchen = zeus_get_post_by_slug( 'kitchen-cabinets', 'page' );
$zeus_page_bath    = zeus_get_post_by_slug( 'bathroom-cabinets-vanities', 'page' );
$zeus_page_ct      = zeus_get_post_by_slug( 'countertops', 'page' );

$zeus_hero_image_id = 159;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Marble Countertops in Orlando', 'zeus' ); ?>">
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
					'alt'           => __( 'Luxury kitchen with natural marble waterfall island and expressive veining', 'zeus' ),
				)
			);
			?>
		<?php endif; ?>
		<div class="zeus-hero__scrim" aria-hidden="true"></div>
	</div>
	<div class="zeus-container">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Marble Countertops', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Marble Countertops in Orlando', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'Natural stone valued for depth, movement and distinctive veining — with a character that changes naturally over time.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Compare Materials', 'zeus' ), 'url' => $zeus_page_ct ? get_permalink( $zeus_page_ct ) . '#zeus-compare' : home_url( '/countertops/#zeus-compare' ), 'variant' => 'secondary', 'on_dark' => true ) ); ?>
			</div>
		</div>
	</div>
</section>

<!-- 2. Why marble -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Why Marble', 'zeus' ),
		'heading' => __( 'Why Homeowners Choose Marble', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<div><h3><?php esc_html_e( 'Genuine Natural Stone', 'zeus' ); ?></h3><p><?php esc_html_e( 'Each slab is unique, quarried rather than manufactured.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Unmistakable Veining', 'zeus' ); ?></h3><p><?php esc_html_e( 'Visual depth and movement that\'s genuinely difficult to reproduce.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Classic Luxury Character', 'zeus' ); ?></h3><p><?php esc_html_e( 'A timeless look homeowners specifically seek out.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Best When Appearance Leads', 'zeus' ); ?></h3><p><?php esc_html_e( 'A strong fit for clients who prioritize the look and accept the care it needs.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. Be clear about care -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Care', 'zeus' ),
		'heading' => __( 'Beautiful — and More Sensitive', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<div>
			<?php echo wp_get_attachment_image( 159, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;', 'alt' => 'Marble countertop with distinctive natural veining' ) ); ?>
		</div>
		<div>
			<p><?php esc_html_e( 'We want to be upfront, not just complimentary: marble can etch — a dulling or light mark on the surface — when exposed to acids like lemon juice, vinegar, or wine, and it can stain if spills aren\'t cleaned up. It also develops a natural patina over time. Sealing helps but doesn\'t prevent all etching or staining.', 'zeus' ); ?></p>
			<p><?php esc_html_e( 'This isn\'t a reason to avoid marble — it\'s context to help you decide whether the look is worth the care it asks for. Many homeowners love that evolving character; others prefer a lower-maintenance material for a high-traffic surface.', 'zeus' ); ?></p>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. Where marble makes sense -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Applications', 'zeus' ),
		'heading' => __( 'Where Marble Makes Sense', 'zeus' ),
		'intro'   => __( 'Marble isn\'t automatically the right fit for every busy kitchen — it tends to make the most sense in these settings.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<div><h3><?php esc_html_e( 'Statement Kitchen Surfaces', 'zeus' ); ?></h3><p><?php esc_html_e( 'For clients comfortable with the care it needs.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Bathroom Vanities', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Islands & Furniture-Style Surfaces', 'zeus' ); ?></h3><p><?php esc_html_e( 'Selected pieces rather than the whole kitchen.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Design-Forward Custom Spaces', 'zeus' ); ?></h3></div>
	</div>
	<p style="margin-top: var(--wp--preset--spacing--3);">
		<?php
		printf(
			/* translators: 1: link to Kitchen Cabinets, 2: link to Bathroom Cabinets & Vanities */
			wp_kses_post( __( 'We\'ll pair marble selection with your %1$s or %2$s during your consultation.', 'zeus' ) ),
			'<a href="' . esc_url( $zeus_page_kitchen ? get_permalink( $zeus_page_kitchen ) : home_url( '/cabinets/kitchen-cabinets/' ) ) . '">' . esc_html__( 'kitchen cabinets', 'zeus' ) . '</a>',
			'<a href="' . esc_url( $zeus_page_bath ? get_permalink( $zeus_page_bath ) : home_url( '/cabinets/bathroom-cabinets-vanities/' ) ) . '">' . esc_html__( 'bathroom vanities', 'zeus' ) . '</a>'
		);
		?>
	</p>
<?php zeus_section_end(); ?>

<!-- 5. Process -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Process', 'zeus' ),
		'heading' => __( 'From Slab Selection to Installation', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Discuss your project, expectations, and care preferences.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Slab Selection', 'zeus' ); ?></h3><p><?php esc_html_e( 'Review the actual slab and its veining together.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Final Measurement / Template', 'zeus' ); ?></h3><p><?php esc_html_e( 'Precise measurements taken once cabinetry is in place.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Fabrication & Installation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Professional fabrication and installation coordinated by ZEUS.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 6. Service area -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Service Area', 'zeus' ),
		'heading' => __( 'Serving Orlando & Central Florida', 'zeus' ),
	)
);
?>
	<p>
		<?php
		esc_html_e(
			'ZEUS installs marble countertops for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.',
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
		'heading' => __( 'Marble Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'Does marble stain?', 'zeus' ),
		'a' => __( 'It can, since marble is more porous than granite or quartz. Prompt cleanup of spills and regular sealing help reduce the risk.', 'zeus' ),
	),
	array(
		'q' => __( 'What is etching?', 'zeus' ),
		'a' => __( 'Etching is a dulling or light mark on the surface caused by contact with acidic substances like lemon juice, vinegar, or wine. It affects the finish, not necessarily the stone\'s structure.', 'zeus' ),
	),
	array(
		'q' => __( 'Does marble need sealing?', 'zeus' ),
		'a' => __( 'Regular sealing is recommended and helps, but it doesn\'t prevent all etching or staining — care and prompt cleanup still matter.', 'zeus' ),
	),
	array(
		'q' => __( 'Is marble suitable for a kitchen?', 'zeus' ),
		'a' => __( 'It can be, especially as a statement surface or lower-traffic accent, for homeowners comfortable with the care it needs. It\'s not automatically the right fit for every busy kitchen.', 'zeus' ),
	),
	array(
		'q' => __( 'How is marble different from quartz?', 'zeus' ),
		'a' => __( 'Marble is natural stone with genuine, one-of-a-kind veining; quartz is engineered for a more consistent, lower-maintenance surface. Quartz and porcelain can also offer marble-inspired patterns with less upkeep.', 'zeus' ),
	),
	array(
		'q' => __( 'Can ZEUS coordinate marble with cabinetry?', 'zeus' ),
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

<!-- 8. Final CTA / consultation form -->
<?php
zeus_section_start(
	array(
		'variant'   => 'compact',
		'eyebrow'   => __( 'Get Started', 'zeus' ),
		'heading'   => __( 'Considering Marble for Your Project?', 'zeus' ),
		'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
