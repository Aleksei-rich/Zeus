<?php
/**
 * Bathroom Cabinets & Vanities service page
 * (/cabinets/bathroom-cabinets-vanities/) -- RC4B.
 * Auto-selected by WordPress's page-{slug}.php template hierarchy for
 * the "bathroom-cabinets-vanities" page (ID 11); no post-meta template
 * assignment needed. Mirrors the RC4A Kitchen Cabinets page pattern
 * (theme/zeus/page-kitchen-cabinets.php) -- same section structure,
 * same positioning rules, adapted content/media for bathroom vanities.
 *
 * Business positioning matches the homepage and Kitchen Cabinets page:
 * in-stock collections AND custom cabinetry are both real, current
 * offerings -- this page must never read as custom-only. No fabricated
 * reviews, stats, certifications, warranty/licensing claims, guaranteed
 * lead times, or completed-project misattribution -- see
 * docs/PROJECT-SPEC.md and .claude/rules/wordpress-development.md.
 */
get_header();
zeus_render_breadcrumbs();

$zeus_page_custom_spaces = zeus_get_post_by_slug( 'custom-spaces', 'page' );
$zeus_page_countertops   = zeus_get_post_by_slug( 'countertops', 'page' );

$zeus_collections = get_posts(
	array(
		'post_type'      => 'cabinet_collection',
		'posts_per_page' => 4,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	)
);

// Hero: Brooklyn Pearl bathroom vanity -- dealer-provided lifestyle/
// product media, visually inspected for branding, not a claimed ZEUS
// completed project (see docs/ASSET-PROVENANCE.csv). Deliberately
// different palette from the Kitchen Cabinets page hero (navy Brooklyn
// Midnight, attachment 114) so the two service pages don't read as
// repeats of each other. LCP element on this page, so eager + high
// priority.
$zeus_hero_image_id = 119;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Bathroom Cabinets & Vanities in Orlando', 'zeus' ); ?>">
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
					'alt'           => __( 'Bright double-sink bathroom vanity with Brooklyn Pearl cabinetry and marble countertop', 'zeus' ),
				)
			);
			?>
		<?php endif; ?>
		<div class="zeus-hero__scrim" aria-hidden="true"></div>
	</div>
	<div class="zeus-container">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Bathroom Cabinetry', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Bathroom Cabinets & Vanities in Orlando', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'In-stock vanity collections for a fast refresh, plus custom bathroom cabinetry for layouts that need an individual solution.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'View Cabinet Styles', 'zeus' ), 'url' => home_url( '/cabinet-styles/' ), 'variant' => 'secondary', 'on_dark' => true ) ); ?>
			</div>
		</div>
	</div>
</section>

<!-- 2. Trust / value strip -->
<?php zeus_section_start( array( 'variant' => 'tight' ) ); ?>
	<h2 class="zeus-visually-hidden"><?php esc_html_e( 'What ZEUS Offers for Bathroom Cabinets', 'zeus' ); ?></h2>
	<div class="zeus-trust-strip">
		<div class="zeus-trust-strip__item">
			<h3><?php esc_html_e( 'In-Stock Options', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Popular vanity styles and finishes available through our central warehouse.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-trust-strip__item">
			<h3><?php esc_html_e( 'Custom Solutions', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Cabinetry for bathrooms with non-standard dimensions or individual needs.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-trust-strip__item">
			<h3><?php esc_html_e( 'Delivery & Installation', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Professional delivery and installation coordinated by ZEUS.', 'zeus' ); ?></p>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. Two ways to build your bathroom -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Two Paths', 'zeus' ),
		'heading' => __( 'Two Ways to Build Your Bathroom', 'zeus' ),
		'intro'   => __( 'A single vanity swap or a full primary-bath remodel can both move quickly with an in-stock collection. Some layouts need cabinetry built to fit. Both are real ZEUS offerings.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--2 zeus-two-paths">
		<article class="zeus-card zeus-two-paths__card">
			<div class="zeus-card__media">
				<?php echo wp_get_attachment_image( 137, 'zeus-card', false, array( 'alt' => 'Oslo Walnut in-stock bathroom vanity cabinets' ) ); ?>
			</div>
			<div class="zeus-card__body">
				<h3><?php esc_html_e( 'In-Stock Bathroom Vanities', 'zeus' ); ?></h3>
				<p><?php esc_html_e( 'A wide range of vanity styles and finishes available through our central warehouse, moving from selection to professional delivery and installation faster than full custom fabrication.', 'zeus' ); ?></p>
				<a class="zeus-btn zeus-btn--secondary" href="<?php echo esc_url( home_url( '/cabinet-styles/' ) ); ?>"><?php esc_html_e( 'Explore Cabinet Styles', 'zeus' ); ?></a>
			</div>
		</article>
		<article class="zeus-card zeus-two-paths__card">
			<div class="zeus-card__media">
				<?php echo wp_get_attachment_image( 128, 'zeus-card', false, array( 'alt' => 'Shaker Kodiak custom bathroom vanity cabinetry with built-in linen storage' ) ); ?>
			</div>
			<div class="zeus-card__body">
				<h3><?php esc_html_e( 'Custom Bathroom Cabinetry', 'zeus' ); ?></h3>
				<p><?php esc_html_e( 'For an unusual footprint, a built-in linen tower, or a vanity that needs to fit an exact wall-to-wall span — cabinetry built to that specification rather than a stock size.', 'zeus' ); ?></p>
				<a class="zeus-btn zeus-btn--secondary" href="<?php echo esc_url( $zeus_page_custom_spaces ? get_permalink( $zeus_page_custom_spaces ) : home_url( '/custom-spaces/' ) ); ?>"><?php esc_html_e( 'See Custom Cabinetry', 'zeus' ); ?></a>
			</div>
		</article>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. Cabinet style / collection discovery -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Collections', 'zeus' ),
		'heading' => __( 'Explore Cabinet Styles for Your Bathroom', 'zeus' ),
		'intro'   => __( 'Vanities are available in the same four collections as our kitchen cabinetry, so your bathroom can match or intentionally contrast with the rest of your home.', 'zeus' ),
	)
);
// Page-specific bathroom-context imagery (RC4B-POLISH): the
// cabinet_collection posts' own featured images are kitchen
// photography (shared sitewide -- e.g. on /cabinet-styles/ and the
// Kitchen Cabinets page), which read as kitchen-context on this
// bathroom-specific landing page. This maps each collection to an
// approved bathroom-context image for THIS page only; the collections'
// own global featured images, titles, excerpts, and permalinks are
// untouched, and components/card-collection.php is not modified. See
// docs/ASSET-PROVENANCE.csv.
$zeus_bath_collection_images = array(
	'brooklyn'        => 119, // Brooklyn Pearl Bathroom (dealer-provided lifestyle)
	'shaker'          => 130, // Shaker Moss Bathroom (dealer-provided lifestyle)
	'oslo'            => 134, // Oslo Oak Bathroom (dealer-provided lifestyle)
	'euro-flat-panel' => 160, // Euro Flat Panel Bathroom Vanity (RC4B generated category/lifestyle visual)
);
?>
	<?php if ( $zeus_collections ) : ?>
		<div class="zeus-grid zeus-grid--4">
			<?php foreach ( $zeus_collections as $zeus_collection_post ) : ?>
				<?php $zeus_bath_image_id = $zeus_bath_collection_images[ $zeus_collection_post->post_name ] ?? 0; ?>
				<article class="zeus-card">
					<?php if ( $zeus_bath_image_id ) : ?>
						<div class="zeus-card__media">
							<?php
							echo wp_get_attachment_image(
								$zeus_bath_image_id,
								'zeus-card',
								false,
								array( 'alt' => get_the_title( $zeus_collection_post ) . ' bathroom vanity cabinetry' )
							);
							?>
						</div>
					<?php endif; ?>
					<div class="zeus-card__body">
						<h3 class="zeus-card__title"><a href="<?php echo esc_url( get_permalink( $zeus_collection_post ) ); ?>"><?php echo esc_html( get_the_title( $zeus_collection_post ) ); ?></a></h3>
						<p class="zeus-card__desc"><?php echo esc_html( get_the_excerpt( $zeus_collection_post ) ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
<?php zeus_section_end(); ?>

<!-- 5. In-stock message -->
<?php
zeus_section_start(
	array(
		'variant' => 'navy',
		'eyebrow' => __( 'In-Stock Advantage', 'zeus' ),
		'heading' => __( 'Popular Vanity Styles, Available for Faster Turnaround', 'zeus' ),
	)
);
?>
	<p class="zeus-door-strip__note"><?php esc_html_e( 'A few examples of the finishes available through our warehouse — not the complete assortment.', 'zeus' ); ?></p>
	<div class="zeus-door-strip">
		<?php
		$zeus_bath_doors = array(
			98  => __( 'Brooklyn Gray', 'zeus' ),
			105 => __( 'Shaker Kodiak', 'zeus' ),
			107 => __( 'Oslo White', 'zeus' ),
		);
		foreach ( $zeus_bath_doors as $zeus_door_id => $zeus_door_label ) :
			?>
			<div class="zeus-door-strip__item">
				<?php echo wp_get_attachment_image( $zeus_door_id, 'zeus-square' ); ?>
				<span class="zeus-door-strip__label"><?php echo esc_html( $zeus_door_label ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
	<p style="margin-top: var(--wp--preset--spacing--3);">
		<?php esc_html_e( 'Popular vanity styles and finishes are available through our central warehouse, helping many bathroom projects move from selection to delivery faster than full custom fabrication.', 'zeus' ); ?>
	</p>
	<p style="margin-top: var(--wp--preset--spacing--2);">
		<a class="zeus-btn zeus-btn--secondary zeus-btn--on-dark" href="<?php echo esc_url( home_url( '/cabinet-styles/' ) ); ?>"><?php esc_html_e( 'View Cabinet Styles', 'zeus' ); ?></a>
	</p>
<?php zeus_section_end(); ?>

<!-- 6. Design / planning / installation -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'Process', 'zeus' ),
		'heading' => __( 'From Vanity Selection to Installation', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step">
			<h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Discuss the bathroom, goals, approximate dimensions, and budget.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-process__step">
			<h3><?php esc_html_e( 'Design & Selection', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Choose an in-stock collection, or determine whether custom cabinetry is the better fit.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-process__step">
			<h3><?php esc_html_e( 'Measure & Confirm', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Final measurements, vanity layout, and project details are confirmed before ordering or fabrication.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-process__step">
			<h3><?php esc_html_e( 'Delivery & Installation', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Professional bathroom cabinet delivery and installation coordinated by ZEUS.', 'zeus' ); ?></p>
		</div>
	</div>
	<p style="margin-top: var(--wp--preset--spacing--3);">
		<?php
		printf(
			/* translators: %s: link to the countertops page */
			esc_html__( 'Vanity tops can also be coordinated as part of the project — see our %s.', 'zeus' ),
			'<a href="' . esc_url( $zeus_page_countertops ? get_permalink( $zeus_page_countertops ) : home_url( '/countertops/' ) ) . '">' . esc_html__( 'countertop materials', 'zeus' ) . '</a>'
		);
		?>
	</p>
<?php zeus_section_end(); ?>

<!-- 7. Real ZEUS trust content -- only one independently-verified real
     bathroom photo currently exists in the approved media library (see
     docs/ASSET-PROVENANCE.csv, attachment 76); shown as a single
     restrained callout rather than padding a 3-photo grid with
     unrelated (kitchen) real photos or non-real lifestyle images
     mislabeled as real. -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Real ZEUS Work', 'zeus' ),
		'heading' => __( 'A Real ZEUS Bathroom Installation', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<div class="zeus-real-photo">
			<?php echo wp_get_attachment_image( 76, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;' ) ); ?>
			<span class="zeus-real-photo__label"><?php esc_html_e( 'Real ZEUS Installation', 'zeus' ); ?></span>
		</div>
		<p><?php esc_html_e( 'A custom double-sink bathroom vanity, delivered and installed by ZEUS — one example of the cabinetry work we do beyond the collection photography on this page.', 'zeus' ); ?></p>
	</div>
<?php zeus_section_end(); ?>

<!-- 8. Countertop cross-sell -->
<?php zeus_section_start( array( 'variant' => 'compact' ) ); ?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<div>
			<?php echo wp_get_attachment_image( 161, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;', 'alt' => 'Bathroom vanity with light stone countertop and undermount sink' ) ); ?>
		</div>
		<div>
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Countertops', 'zeus' ); ?></p>
			<h2><?php esc_html_e( 'Vanity Tops & Cabinetry, Coordinated Together', 'zeus' ); ?></h2>
			<p><?php esc_html_e( 'Marble and quartz are both common vanity-top choices, alongside granite and porcelain. ZEUS coordinates the countertop material with your cabinetry so the whole bathroom comes together on one timeline.', 'zeus' ); ?></p>
			<a class="zeus-btn zeus-btn--secondary" href="<?php echo esc_url( $zeus_page_countertops ? get_permalink( $zeus_page_countertops ) : home_url( '/countertops/' ) ); ?>"><?php esc_html_e( 'Explore Countertops', 'zeus' ); ?></a>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 9. Service area -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Service Area', 'zeus' ),
		'heading' => __( 'Bathroom Cabinets Across Orlando & Central Florida', 'zeus' ),
	)
);
?>
	<p>
		<?php
		esc_html_e(
			'ZEUS installs in-stock and custom bathroom cabinets and vanities for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.',
			'zeus'
		);
		?>
	</p>
	<p class="zeus-service-area__note">
		<?php esc_html_e( "Not sure if you're in our service area?", 'zeus' ); ?>
		<a href="<?php echo esc_url( zeus_phone_number_href() ); ?>"><?php esc_html_e( 'Call or text us.', 'zeus' ); ?></a>
	</p>
<?php zeus_section_end(); ?>

<!-- 10. Page-specific FAQ -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'FAQ', 'zeus' ),
		'heading' => __( 'Bathroom Cabinet Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'Does ZEUS offer in-stock and custom bathroom vanities?', 'zeus' ),
		'a' => __( 'Yes. We stock popular vanity styles and finishes through a central warehouse for a fast refresh, and we build custom bathroom cabinetry for non-standard dimensions and individual layouts.', 'zeus' ),
	),
	array(
		'q' => __( 'What bathroom vanity styles do you offer?', 'zeus' ),
		'a' => __( 'The same four collections as our kitchen cabinetry: transitional full-overlay Brooklyn, traditional five-piece Shaker, the narrower-rail Slim Shaker Oslo collection (including the Classic Walnut finish), and minimalist Euro / Flat Panel.', 'zeus' ),
	),
	array(
		'q' => __( 'Can I replace a single vanity, or does it need to be a full remodel?', 'zeus' ),
		'a' => __( 'Either. A single in-stock vanity replacement and a full primary-bath remodel are both projects we take on.', 'zeus' ),
	),
	array(
		'q' => __( 'Does ZEUS install bathroom cabinets?', 'zeus' ),
		'a' => __( 'Yes — professional bathroom cabinet delivery and installation is coordinated by ZEUS for both in-stock and custom cabinetry.', 'zeus' ),
	),
	array(
		'q' => __( 'Can ZEUS coordinate a vanity top with my cabinet project?', 'zeus' ),
		'a' => __( 'Yes. Marble, quartz, granite, and porcelain vanity tops can be planned and coordinated alongside your bathroom cabinets as one project.', 'zeus' ),
	),
	array(
		'q' => __( 'Do you have a showroom?', 'zeus' ),
		'a' => __( 'ZEUS is a service-area business — we meet with you for a consultation rather than operating a public walk-in showroom.', 'zeus' ),
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

<!-- 11. Final CTA / consultation form -->
<?php
zeus_section_start(
	array(
		'variant'   => 'compact',
		'eyebrow'   => __( 'Get Started', 'zeus' ),
		'heading'   => __( 'Ready to Plan Your Bathroom?', 'zeus' ),
		'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
