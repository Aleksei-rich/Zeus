<?php
/**
 * Kitchen Cabinets service page (/cabinets/kitchen-cabinets/) -- RC4A.
 * Auto-selected by WordPress's page-{slug}.php template hierarchy for
 * the "kitchen-cabinets" page (ID 10); no post-meta template assignment
 * needed. Business positioning matches the homepage (see
 * front-page.php header comment and docs/DECISIONS.md): in-stock
 * collections AND custom cabinetry are both real, current offerings --
 * this page must never read as custom-only.
 *
 * No fabricated reviews, stats, certifications, warranty/licensing
 * claims, guaranteed lead times, or completed-project misattribution --
 * see docs/PROJECT-SPEC.md and .claude/rules/wordpress-development.md.
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

// Hero: Brooklyn Midnight kitchen -- dealer-provided lifestyle/product
// media, visually inspected for branding, not a claimed ZEUS completed
// project (see docs/ASSET-PROVENANCE.csv). Deliberately different from
// the homepage hero image (Oslo Walnut, attachment 135). LCP element on
// this page, so eager + high priority.
$zeus_hero_image_id = 114;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Kitchen Cabinets in Orlando', 'zeus' ); ?>">
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
					'alt'           => __( 'Navy Shaker-style kitchen cabinets with a center island, part of the Brooklyn collection', 'zeus' ),
				)
			);
			?>
		<?php endif; ?>
		<div class="zeus-hero__scrim" aria-hidden="true"></div>
	</div>
	<div class="zeus-container">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Kitchen Cabinetry', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Kitchen Cabinets in Orlando', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'In-stock cabinet collections for fast turnaround, plus custom cabinetry for kitchens that need an individual solution.', 'zeus' ); ?>
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
	<h2 class="zeus-visually-hidden"><?php esc_html_e( 'What ZEUS Offers for Kitchen Cabinets', 'zeus' ); ?></h2>
	<div class="zeus-trust-strip">
		<div class="zeus-trust-strip__item">
			<h3><?php esc_html_e( 'In-Stock Options', 'zeus' ); ?></h3>
                    <p><?php esc_html_e( 'All standard cabinet colors and finishes are available in stock through our central warehouse, except Hyper Colors and Euro / Flat Panel styles.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-trust-strip__item">
			<h3><?php esc_html_e( 'Custom Solutions', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Cabinetry for kitchens with non-standard dimensions or individual needs.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-trust-strip__item">
			<h3><?php esc_html_e( 'Delivery & Installation', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Professional delivery and installation coordinated by ZEUS.', 'zeus' ); ?></p>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. Two ways to build your kitchen -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Two Paths', 'zeus' ),
		'heading' => __( 'Two Ways to Build Your Kitchen', 'zeus' ),
		'intro'   => __( 'Most kitchens move fastest with an in-stock collection. Some need cabinetry built to fit. Choose the approach that best fits your space, timeline, and design needs.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--2 zeus-two-paths">
		<article class="zeus-card zeus-two-paths__card">
			<div class="zeus-card__media">
				<?php echo wp_get_attachment_image( 123, 'zeus-card', false, array( 'alt' => 'Shaker White in-stock kitchen cabinets' ) ); ?>
			</div>
			<div class="zeus-card__body">
				<h3><?php esc_html_e( 'In-Stock Kitchen Cabinetry', 'zeus' ); ?></h3>
                            <p><?php esc_html_e( 'Most kitchen projects can use our in-stock cabinet program: all standard cabinet colors and finishes are available through our central warehouse, except Hyper Colors and Euro / Flat Panel styles.', 'zeus' ); ?></p>
				<a class="zeus-btn zeus-btn--secondary" href="<?php echo esc_url( home_url( '/cabinet-styles/' ) ); ?>"><?php esc_html_e( 'Explore Cabinet Styles', 'zeus' ); ?></a>
			</div>
		</article>
		<article class="zeus-card zeus-two-paths__card">
			<div class="zeus-card__media">
				<?php echo wp_get_attachment_image( 122, 'zeus-card', false, array( 'alt' => 'Brooklyn Slate custom kitchen cabinetry with wall-to-wall cabinetry and a built-in, ceiling-height appliance surround' ) ); ?>
			</div>
			<div class="zeus-card__body">
				<h3><?php esc_html_e( 'Custom Kitchen Cabinetry', 'zeus' ); ?></h3>
				<p><?php esc_html_e( 'For non-standard dimensions, special layouts, and built-in details a stocked size can\'t solve — cabinetry fabricated to your exact kitchen rather than shipped from stock.', 'zeus' ); ?></p>
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
		'heading' => __( 'Explore Kitchen Cabinet Styles', 'zeus' ),
		'intro'   => __( 'From transitional Brooklyn to clean Euro flat-panel — each collection is available in multiple finishes for your kitchen.', 'zeus' ),
	)
);
?>
	<?php if ( $zeus_collections ) : ?>
		<div class="zeus-grid zeus-grid--4">
			<?php foreach ( $zeus_collections as $zeus_collection_post ) : ?>
				<?php get_template_part( 'components/card-collection', null, array( 'post' => $zeus_collection_post ) ); ?>
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
		'heading' => __( 'Popular Cabinet Styles, Available for Faster Turnaround', 'zeus' ),
	)
);
?>
	<p class="zeus-door-strip__note"><?php esc_html_e( 'A few representative examples of the finishes available through our warehouse — not the complete assortment.', 'zeus' ); ?></p>
	<div class="zeus-door-strip">
		<?php
		$zeus_kitchen_doors = array(
			100 => __( 'Brooklyn White', 'zeus' ),
			103 => __( 'Shaker White', 'zeus' ),
			107 => __( 'Oslo White', 'zeus' ),
			99  => __( 'Brooklyn Midnight', 'zeus' ),
			106 => __( 'Shaker Moss', 'zeus' ),
			109 => __( 'Oslo Walnut', 'zeus' ),
		);
		foreach ( $zeus_kitchen_doors as $zeus_door_id => $zeus_door_label ) :
			?>
			<div class="zeus-door-strip__item">
				<?php echo wp_get_attachment_image( $zeus_door_id, 'zeus-square' ); ?>
				<span class="zeus-door-strip__label"><?php echo esc_html( $zeus_door_label ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
	<p style="margin-top: var(--wp--preset--spacing--3);">
            <?php esc_html_e( 'All standard cabinet colors and finishes are available in stock through our central warehouse. Hyper Colors and Euro / Flat Panel styles are the only exceptions and are not kept in stock.', 'zeus' ); ?>
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
		'heading' => __( 'From Cabinet Selection to Installation', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step">
			<h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Discuss the kitchen, goals, approximate dimensions, and budget.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-process__step">
			<h3><?php esc_html_e( 'Design & Selection', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Choose an in-stock collection, or determine whether custom cabinetry is the better fit.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-process__step">
			<h3><?php esc_html_e( 'Measure & Confirm', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Final measurements, cabinet layout, and project details are confirmed before ordering or fabrication.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-process__step">
			<h3><?php esc_html_e( 'Delivery & Installation', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Professional kitchen cabinet delivery and installation coordinated by ZEUS.', 'zeus' ); ?></p>
		</div>
	</div>
	<p style="margin-top: var(--wp--preset--spacing--3);">
		<?php
		printf(
			/* translators: %s: link to the countertops page */
			esc_html__( 'Countertops can also be coordinated as part of the project — see our %s.', 'zeus' ),
			'<a href="' . esc_url( $zeus_page_countertops ? get_permalink( $zeus_page_countertops ) : home_url( '/countertops/' ) ) . '">' . esc_html__( 'countertop materials', 'zeus' ) . '</a>'
		);
		?>
	</p>
<?php zeus_section_end(); ?>

<!-- 7. Real ZEUS trust content -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Real ZEUS Work', 'zeus' ),
		'heading' => __( 'Real ZEUS Kitchen Installations', 'zeus' ),
		'intro'   => __( 'Actual installation photos from ZEUS projects.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--3">
		<?php foreach ( array( 75, 74, 77 ) as $zeus_real_photo_id ) : ?>
			<div class="zeus-real-photo">
				<?php echo wp_get_attachment_image( $zeus_real_photo_id, 'zeus-card' ); ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php zeus_section_end(); ?>

<!-- 8. Countertop cross-sell -->
<?php zeus_section_start( array( 'variant' => 'compact' ) ); ?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<div>
			<?php echo wp_get_attachment_image( 156, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;' ) ); ?>
		</div>
		<div>
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Countertops', 'zeus' ); ?></p>
			<h2><?php esc_html_e( 'Cabinets & Countertops, Coordinated Together', 'zeus' ); ?></h2>
			<p><?php esc_html_e( 'Your kitchen cabinets and countertops are planned as one project, not two. ZEUS coordinates quartz, granite, porcelain, and marble alongside your cabinetry so the whole kitchen comes together on one timeline.', 'zeus' ); ?></p>
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
		'heading' => __( 'Kitchen Cabinets Across Orlando & Central Florida', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<p style="margin:0;">
			<?php
			esc_html_e(
				'ZEUS installs in-stock and custom kitchen cabinets for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.',
				'zeus'
			);
			?>
		</p>
		<div class="zeus-card">
			<div class="zeus-card__body">
				<p style="margin:0; font-weight:700;"><?php esc_html_e( 'Serving Orlando & surrounding communities', 'zeus' ); ?></p>
				<p style="margin:0;">
					<?php esc_html_e( 'Call or text us to confirm service for your location.', 'zeus' ); ?>
					<br>
					<a href="<?php echo esc_url( zeus_phone_number_href() ); ?>"><?php echo esc_html( zeus_phone_number_display() ); ?></a>
				</p>
			</div>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 10. Page-specific FAQ -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'FAQ', 'zeus' ),
		'heading' => __( 'Kitchen Cabinet Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'Does ZEUS offer in-stock and custom kitchen cabinets?', 'zeus' ),
            'a' => __( 'Yes. All standard cabinet colors and finishes are available in stock through our central warehouse, except Hyper Colors and Euro / Flat Panel styles. We also build custom kitchen cabinetry for non-standard dimensions and individual layouts.', 'zeus' ),
	),
	array(
		'q' => __( 'What kitchen cabinet styles do you offer?', 'zeus' ),
            'a' => __( 'Four collections: transitional full-overlay Brooklyn, traditional five-piece Shaker, the narrower-rail Slim Shaker Oslo collection (including the Classic Walnut finish), and minimalist Euro / Flat Panel.', 'zeus' ),
	),
	array(
		'q' => __( 'What is the difference between Shaker and Slim Shaker cabinets?', 'zeus' ),
		'a' => __( 'Traditional Shaker (our Shaker collection) uses a standard five-piece recessed-panel door. Slim Shaker (our Oslo collection) uses a narrower rail for a more streamlined, modern look.', 'zeus' ),
	),
	array(
		'q' => __( 'Does ZEUS install kitchen cabinets?', 'zeus' ),
		'a' => __( 'Yes — professional kitchen cabinet delivery and installation is coordinated by ZEUS for both in-stock and custom cabinetry.', 'zeus' ),
	),
	array(
		'q' => __( 'Can ZEUS coordinate countertops with my cabinet project?', 'zeus' ),
		'a' => __( 'Yes. Quartz, granite, porcelain, and marble countertops can be planned and coordinated alongside your kitchen cabinets as one project.', 'zeus' ),
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
		'heading'   => __( 'Ready to Plan Your Kitchen?', 'zeus' ),
		'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
