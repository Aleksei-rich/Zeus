<?php
/**
 * Homepage — 13 sections per docs/SITE-ARCHITECTURE.md:
 * Hero, Trust, Services, Collections, Countertops, Process, Featured
 * Projects, Why ZEUS, Reviews, Service Area, FAQ, Consultation CTA/form,
 * Footer (footer.php).
 *
 * No fabricated reviews, stats, certifications, or completed-project
 * claims anywhere on this template — see docs/PROJECT-SPEC.md.
 */
get_header();

// NOTE: get_page_by_path() requires the FULL ancestor path for non-top-
// level pages (returns null for a bare child slug) — zeus_get_post_by_slug()
// (defined by the zeus-core plugin) does a plain, depth-independent
// post_name lookup instead. See docs/DECISIONS.md, "Seeding safety fix".
$zeus_page_cabinets     = zeus_get_post_by_slug( 'cabinets', 'page' );
$zeus_page_kitchen      = zeus_get_post_by_slug( 'kitchen-cabinets', 'page' );
$zeus_page_bath         = zeus_get_post_by_slug( 'bathroom-cabinets-vanities', 'page' );
$zeus_page_countertops  = zeus_get_post_by_slug( 'countertops', 'page' );
$zeus_page_quartz       = zeus_get_post_by_slug( 'quartz', 'page' );
$zeus_page_granite      = zeus_get_post_by_slug( 'granite', 'page' );
$zeus_page_porcelain    = zeus_get_post_by_slug( 'porcelain', 'page' );
$zeus_page_marble       = zeus_get_post_by_slug( 'marble', 'page' );

$zeus_collections = get_posts(
	array(
		'post_type'      => 'cabinet_collection',
		'posts_per_page' => 4,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	)
);

$zeus_featured_projects = get_posts(
	array(
		'post_type'      => 'project',
		'posts_per_page' => 3,
		'meta_key'       => 'zeus_is_featured',
		'meta_value'     => '1',
	)
);

// Real ZEUS project photography (see docs/ASSET-PROVENANCE.csv) — this
// is the page's LCP element, so it loads eager + high priority, not lazy.
$zeus_hero_image_post = zeus_get_post_by_slug( 'custom-kitchen-with-island-seating', 'attachment' );
$zeus_hero_image_id   = $zeus_hero_image_post ? $zeus_hero_image_post->ID : 0;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Introduction', 'zeus' ); ?>">
	<div class="zeus-container zeus-hero__grid">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Orlando & Central Florida', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Custom Kitchen Cabinets & Countertops', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'ZEUS designs and builds custom kitchen and bathroom cabinetry and countertops for homeowners in Orlando, Windermere, Winter Garden, Horizon West, Clermont, and Dr. Phillips.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => zeus_phone_number_display(), 'url' => zeus_phone_number_href(), 'variant' => 'secondary' ) ); ?>
			</div>
		</div>
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
					)
				);
				?>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- 2. Trust / value proposition -->
<?php zeus_section_start( array( 'variant' => 'tight' ) ); ?>
	<h2 class="zeus-visually-hidden"><?php esc_html_e( 'Why Homeowners Trust ZEUS', 'zeus' ); ?></h2>
	<div class="zeus-trust-strip">
		<div class="zeus-trust-strip__item">
			<h3><?php esc_html_e( 'Custom-Built', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Designed around your space, not picked from a generic catalog.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-trust-strip__item">
			<h3><?php esc_html_e( 'Orlando & Central Florida', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Serving Orlando, Windermere, Winter Garden, Horizon West, Clermont, and Dr. Phillips.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-trust-strip__item">
			<h3><?php esc_html_e( 'Free Consultation', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'A no-obligation first conversation about your project.', 'zeus' ); ?></p>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. Main services -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Services', 'zeus' ),
		'heading' => __( 'What We Build', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--3">
		<?php
		get_template_part( 'components/card-service', null, array(
			'title' => __( 'Kitchen Cabinets', 'zeus' ),
			'desc'  => __( 'Custom kitchen cabinetry designed around how your kitchen is actually used.', 'zeus' ),
			'url'   => $zeus_page_kitchen ? get_permalink( $zeus_page_kitchen ) : home_url( '/cabinets/kitchen-cabinets/' ),
		) );
		get_template_part( 'components/card-service', null, array(
			'title' => __( 'Bathroom Cabinets & Vanities', 'zeus' ),
			'desc'  => __( 'Custom vanities and bathroom cabinetry, from a single vanity to a full remodel.', 'zeus' ),
			'url'   => $zeus_page_bath ? get_permalink( $zeus_page_bath ) : home_url( '/cabinets/bathroom-cabinets-vanities/' ),
		) );
		get_template_part( 'components/card-service', null, array(
			'title' => __( 'Countertops', 'zeus' ),
			'desc'  => __( 'Quartz, granite, porcelain, and marble countertops matched to how you use your space.', 'zeus' ),
			'url'   => $zeus_page_countertops ? get_permalink( $zeus_page_countertops ) : home_url( '/countertops/' ),
		) );
		?>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. Featured cabinet collections -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Cabinet Styles', 'zeus' ),
		'heading' => __( 'Featured Collections', 'zeus' ),
		'intro'   => __( 'From transitional Brooklyn to Slim Shaker Oslo — including the OSLO Classic Walnut Slim Shaker collection.', 'zeus' ),
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

<!-- 5. Countertops -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Countertops', 'zeus' ),
		'heading' => __( 'Countertop Materials', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<?php
		$zeus_materials = array(
			array( 'title' => __( 'Quartz', 'zeus' ), 'page' => $zeus_page_quartz, 'slug' => 'quartz' ),
			array( 'title' => __( 'Granite', 'zeus' ), 'page' => $zeus_page_granite, 'slug' => 'granite' ),
			array( 'title' => __( 'Porcelain', 'zeus' ), 'page' => $zeus_page_porcelain, 'slug' => 'porcelain' ),
			array( 'title' => __( 'Marble', 'zeus' ), 'page' => $zeus_page_marble, 'slug' => 'marble' ),
		);
		foreach ( $zeus_materials as $zeus_material ) :
			get_template_part( 'components/card-service', null, array(
				'title' => $zeus_material['title'],
				'desc'  => get_the_excerpt( $zeus_material['page'] ) ? get_the_excerpt( $zeus_material['page'] ) : '',
				'url'   => $zeus_material['page'] ? get_permalink( $zeus_material['page'] ) : home_url( '/countertops/' . $zeus_material['slug'] . '/' ),
			) );
		endforeach;
		?>
	</div>
<?php zeus_section_end(); ?>

<!-- 6. Process -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Process', 'zeus' ),
		'heading' => __( 'How It Works', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3><p><?php esc_html_e( 'We talk through your space, goals, and budget.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Design', 'zeus' ); ?></h3><p><?php esc_html_e( 'We plan the layout, style, and materials with you.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Build', 'zeus' ); ?></h3><p><?php esc_html_e( 'Your cabinetry and countertops are built to spec.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Install', 'zeus' ); ?></h3><p><?php esc_html_e( 'We install and finish the space, ready to use.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 7. Featured real projects — only rendered once real, confidently-
     grouped completed projects exist (see docs/DECISIONS.md, "Premier
     series"/"Builder series" finding); no placeholder projects shown. -->
<?php if ( $zeus_featured_projects ) : ?>
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Portfolio', 'zeus' ),
		'heading' => __( 'Featured Projects', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--3">
		<?php foreach ( $zeus_featured_projects as $zeus_project_post ) : ?>
			<?php get_template_part( 'components/card-project', null, array( 'post' => $zeus_project_post ) ); ?>
		<?php endforeach; ?>
	</div>
	<p style="margin-top: var(--wp--preset--spacing--3);">
		<a class="zeus-btn zeus-btn--secondary" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>"><?php esc_html_e( 'View Full Portfolio', 'zeus' ); ?></a>
	</p>
<?php zeus_section_end(); ?>
<?php endif; ?>

<!-- 8. Why ZEUS -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Why ZEUS', 'zeus' ),
		'heading' => __( 'Why Homeowners Choose ZEUS', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<div><h3><?php esc_html_e( 'Custom, Not Catalog', 'zeus' ); ?></h3><p><?php esc_html_e( 'Every project is designed for the specific space, not assembled from stock sizes.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'One Team, Start to Finish', 'zeus' ); ?></h3><p><?php esc_html_e( 'Design, cabinetry, and countertops handled by one team, not separate subcontractors.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Clear Pricing Up Front', 'zeus' ); ?></h3><p><?php esc_html_e( 'You get a clear estimate before work begins, so there are no surprises on the final invoice.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Local to Central Florida', 'zeus' ); ?></h3><p><?php esc_html_e( 'Focused on the Orlando area rather than spread across the whole state.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 9. Reviews — clean architecture, no fabricated review text. ZEUS has
     a real, active Google Business Profile with an existing reviews
     feed (see docs/OLD-SITE-INVENTORY.md, "Existing review integration");
     this section is built to receive that live feed once the owner
     provides the Google Place/API connection. No AggregateRating
     schema is added until real review data is actually connected. -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Reviews', 'zeus' ),
		'heading' => __( 'What Customers Say', 'zeus' ),
	)
);
?>
	<div class="zeus-container--narrow" style="text-align:center;">
		<p><?php esc_html_e( 'ZEUS reviews are collected through our Google Business Profile. We\'re connecting that live feed to this page — check back soon, or search for ZEUS Cabinets & Countertops on Google to read verified reviews today.', 'zeus' ); ?></p>
	</div>
<?php zeus_section_end(); ?>

<!-- 10. Service area -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Service Area', 'zeus' ),
		'heading' => __( 'Where We Work', 'zeus' ),
	)
);
?>
	<div class="zeus-area-list">
		<?php foreach ( array( 'Orlando', 'Windermere', 'Winter Garden', 'Horizon West', 'Clermont', 'Dr. Phillips' ) as $zeus_area ) : ?>
			<span class="zeus-area-list__chip"><?php echo zeus_icon( 'location' ); // phpcs:ignore ?> <?php echo esc_html( $zeus_area ); ?></span>
		<?php endforeach; ?>
	</div>
<?php zeus_section_end(); ?>

<!-- 11. FAQ -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'FAQ', 'zeus' ),
		'heading' => __( 'Frequently Asked Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'Do you have a showroom I can visit?', 'zeus' ),
		'a' => __( 'ZEUS is a service-area business — we meet with you for a consultation rather than operating a public walk-in showroom.', 'zeus' ),
	),
	array(
		'q' => __( 'What areas do you serve?', 'zeus' ),
		'a' => __( 'We serve Orlando, Windermere, Winter Garden, Horizon West, Clermont, and Dr. Phillips.', 'zeus' ),
	),
	array(
		'q' => __( 'What\'s the difference between Shaker and Slim Shaker?', 'zeus' ),
		'a' => __( 'Traditional Shaker (our Shaker collection) uses a standard five-piece recessed-panel door. Slim Shaker (our Oslo collection, including the Classic Walnut finish) uses a narrower rail for a more streamlined look.', 'zeus' ),
	),
	array(
		'q' => __( 'What countertop material is right for me?', 'zeus' ),
		'a' => __( 'It depends on how the space is used — quartz and porcelain are low-maintenance and non-porous, granite and marble are natural stone with more character and, for marble especially, more care required. We\'ll talk through the tradeoffs during your consultation.', 'zeus' ),
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

<!-- 12. Request Free Consultation CTA/form -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Get Started', 'zeus' ),
		'heading' => __( 'Request Your Free Consultation', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
