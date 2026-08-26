<?php
/**
 * Porcelain Countertops page (/countertops/porcelain/) -- RC4D.
 * Auto-selected by WordPress's page-{slug}.php template hierarchy for
 * the "porcelain" page (ID 15); no post-meta template assignment
 * needed. No exaggerated claims (unbreakable, chip-proof, completely
 * scratch-proof) -- see docs/DECISIONS.md ("RC4D"). Attachment 158 is
 * a generated category/lifestyle visual (see docs/ASSET-PROVENANCE.csv)
 * and must never be presented as a completed ZEUS project. No
 * independently-verified real ZEUS porcelain-specific installation
 * photo exists, so this page has no "Real ZEUS Work" section.
 */
get_header();
zeus_render_breadcrumbs();

$zeus_page_kitchen = zeus_get_post_by_slug( 'kitchen-cabinets', 'page' );
$zeus_page_bath    = zeus_get_post_by_slug( 'bathroom-cabinets-vanities', 'page' );
$zeus_page_ct      = zeus_get_post_by_slug( 'countertops', 'page' );

$zeus_hero_image_id = 158;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Porcelain Countertops in Orlando', 'zeus' ); ?>">
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
					'alt'           => __( 'Modern kitchen with large-format porcelain countertop and matching slab backsplash', 'zeus' ),
				)
			);
			?>
		<?php endif; ?>
		<div class="zeus-hero__scrim" aria-hidden="true"></div>
	</div>
	<div class="zeus-container">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Porcelain Countertops', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Porcelain Countertops in Orlando', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'A modern manufactured slab surface well suited to contemporary interiors and large-format architectural applications.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Compare Materials', 'zeus' ), 'url' => $zeus_page_ct ? get_permalink( $zeus_page_ct ) . '#zeus-compare' : home_url( '/countertops/#zeus-compare' ), 'variant' => 'secondary', 'on_dark' => true ) ); ?>
			</div>
		</div>
	</div>
</section>

<!-- 2. Why porcelain -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Why Porcelain', 'zeus' ),
		'heading' => __( 'Why Homeowners Choose Porcelain', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<div><h3><?php esc_html_e( 'Modern Appearance', 'zeus' ); ?></h3><p><?php esc_html_e( 'A clean, contemporary look that suits architectural interiors.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Large-Format Slabs', 'zeus' ); ?></h3><p><?php esc_html_e( 'Fewer visible seams across bigger countertop runs.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Slim Visual Profiles', 'zeus' ); ?></h3><p><?php esc_html_e( 'A thinner-profile look can be possible depending on the slab and fabrication.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Stone-Look or Minimalist', 'zeus' ); ?></h3><p><?php esc_html_e( 'Designs can reproduce a natural-stone look or a clean minimalist finish.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. Fabrication details matter -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Fabrication', 'zeus' ),
		'heading' => __( 'A Material Where Fabrication Details Matter', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<div>
			<?php echo wp_get_attachment_image( 158, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;', 'alt' => 'Large-format porcelain countertop and matching backsplash' ) ); ?>
		</div>
		<div>
			<p><?php esc_html_e( 'Edge construction, seams, support, and the overall fabrication approach should be planned for the specific slab and application. Porcelain\'s rigidity means these details matter more than they do with some other materials — professional fabrication and installation help avoid issues at edges and corners.', 'zeus' ); ?></p>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. Applications -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Applications', 'zeus' ),
		'heading' => __( 'Where Porcelain Works', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<div><h3><?php esc_html_e( 'Contemporary Kitchen Countertops', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Bathroom Vanities', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Waterfall / Slab-Forward Designs', 'zeus' ); ?></h3><p><?php esc_html_e( 'Where appropriate for the layout.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Coordinated Backsplash', 'zeus' ); ?></h3><p><?php esc_html_e( 'Where fabrication allows.', 'zeus' ); ?></p></div>
	</div>
	<p style="margin-top: var(--wp--preset--spacing--3);">
		<?php
		printf(
			/* translators: 1: link to Kitchen Cabinets, 2: link to Bathroom Cabinets & Vanities */
			wp_kses_post( __( 'We\'ll pair porcelain selection with your %1$s or %2$s during your consultation.', 'zeus' ) ),
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
		'variant' => 'compact',
		'eyebrow' => __( 'Process', 'zeus' ),
		'heading' => __( 'From Selection to Installation', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Discuss your project and cabinetry pairing.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Slab Selection', 'zeus' ); ?></h3><p><?php esc_html_e( 'Choose a finish and plan the fabrication approach.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Final Measurement / Template', 'zeus' ); ?></h3><p><?php esc_html_e( 'Precise measurements taken once cabinetry is in place.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Fabrication & Installation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Professional fabrication and installation coordinated by ZEUS.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 6. Service area -->
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
			'ZEUS installs porcelain countertops for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.',
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
		'heading' => __( 'Porcelain Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'What is a porcelain countertop?', 'zeus' ),
		'a' => __( 'Porcelain slab is a manufactured material offering a modern appearance and large-format design possibilities.', 'zeus' ),
	),
	array(
		'q' => __( 'Is porcelain the same as quartz?', 'zeus' ),
		'a' => __( 'No — porcelain is a manufactured slab material with its own fabrication considerations, while quartz is engineered from quartz mineral bound with resin. They look and behave differently.', 'zeus' ),
	),
	array(
		'q' => __( 'Can porcelain be used for a kitchen island?', 'zeus' ),
		'a' => __( 'Yes, including waterfall or slab-forward designs where the layout and fabrication allow.', 'zeus' ),
	),
	array(
		'q' => __( 'Can porcelain have a stone-like appearance?', 'zeus' ),
		'a' => __( 'Yes, many porcelain designs reproduce natural-stone patterns alongside solid, minimalist finishes.', 'zeus' ),
	),
	array(
		'q' => __( 'Do edge details matter with porcelain?', 'zeus' ),
		'a' => __( 'Yes — edge construction and seam planning matter more with porcelain than with some other materials, so professional fabrication is important.', 'zeus' ),
	),
	array(
		'q' => __( 'Can ZEUS coordinate porcelain with cabinetry?', 'zeus' ),
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
		'heading'   => __( 'Considering Porcelain for Your Project?', 'zeus' ),
		'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
