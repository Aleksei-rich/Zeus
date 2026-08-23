<?php
/**
 * Homepage — Hero, Trust, Two Paths (In-Stock/Custom), In-Stock
 * Collections, Why In-Stock Matters, Countertops, Custom Spaces,
 * Process, Real ZEUS Photography, Why ZEUS, Reviews, Service Area,
 * FAQ, Consultation CTA/form, Footer (footer.php).
 *
 * Business positioning (Phase 5 / RC2): ZEUS has two cabinetry
 * offerings — in-stock collections available through a central
 * warehouse for fast turnaround, and custom cabinetry/furniture for
 * closets, built-ins, and non-standard spaces. Copy throughout this
 * template is written to keep both visible; "custom" is a positive
 * capability, not the only offering. See docs/DECISIONS.md.
 *
 * No fabricated reviews, stats, certifications, or completed-project
 * claims anywhere on this template — see docs/PROJECT-SPEC.md.
 */
get_header();

// NOTE: get_page_by_path() requires the FULL ancestor path for non-top-
// level pages (returns null for a bare child slug) — zeus_get_post_by_slug()
// (defined by the zeus-core plugin) does a plain, depth-independent
// post_name lookup instead. See docs/DECISIONS.md, "Seeding safety fix".
$zeus_page_cabinets      = zeus_get_post_by_slug( 'cabinets', 'page' );
$zeus_page_kitchen       = zeus_get_post_by_slug( 'kitchen-cabinets', 'page' );
$zeus_page_bath          = zeus_get_post_by_slug( 'bathroom-cabinets-vanities', 'page' );
$zeus_page_countertops   = zeus_get_post_by_slug( 'countertops', 'page' );
$zeus_page_quartz        = zeus_get_post_by_slug( 'quartz', 'page' );
$zeus_page_granite       = zeus_get_post_by_slug( 'granite', 'page' );
$zeus_page_porcelain     = zeus_get_post_by_slug( 'porcelain', 'page' );
$zeus_page_marble        = zeus_get_post_by_slug( 'marble', 'page' );
$zeus_page_custom_spaces = zeus_get_post_by_slug( 'custom-spaces', 'page' );
$zeus_page_closets       = zeus_get_post_by_slug( 'closets', 'page' );
$zeus_page_laundry       = zeus_get_post_by_slug( 'laundry-pantry', 'page' );
$zeus_page_home_office   = zeus_get_post_by_slug( 'home-office', 'page' );

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

// Hero background: curated Oslo Walnut (OSLO Classic Walnut Slim
// Shaker) lifestyle photography — dealer-provided lifestyle/product
// media, not a claimed ZEUS completed project (see
// docs/ASSET-PROVENANCE.csv). This is the page's LCP element, so it
// loads eager + high priority, not lazy.
$zeus_hero_image_id = 135; // oslo-walnut-kitchen-01
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Introduction', 'zeus' ); ?>">
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
		<div class="zeus-hero__scrim" aria-hidden="true"></div>
	</div>
	<div class="zeus-container">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'In-Stock + Custom Cabinetry', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Kitchen Cabinets & Countertops in Orlando', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'In-stock cabinet collections for fast turnaround, plus custom cabinetry and built-ins throughout Central Florida.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'View Cabinet Styles', 'zeus' ), 'url' => home_url( '/cabinet-styles/' ), 'variant' => 'secondary', 'on_dark' => true ) ); ?>
			</div>
		</div>
	</div>
</section>

<!-- 2. Trust / value proposition -->
<?php zeus_section_start( array( 'variant' => 'tight' ) ); ?>
	<h2 class="zeus-visually-hidden"><?php esc_html_e( 'Why Homeowners Trust ZEUS', 'zeus' ); ?></h2>
	<div class="zeus-trust-strip">
		<div class="zeus-trust-strip__item">
			<h3><?php esc_html_e( 'Fast Turnaround', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Popular styles ship from our central warehouse — no waiting on full custom fabrication.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-trust-strip__item">
			<h3><?php esc_html_e( 'Custom When It Counts', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'Closets, built-ins, and unique spaces, made to fit.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-trust-strip__item">
			<h3><?php esc_html_e( 'Free Consultation', 'zeus' ); ?></h3>
			<p><?php esc_html_e( 'A no-obligation first conversation about your project.', 'zeus' ); ?></p>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. Two paths: in-stock vs. custom -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Two Ways to Work With ZEUS', 'zeus' ),
		'heading' => __( 'In-Stock Cabinetry or Custom-Built — Your Call', 'zeus' ),
		'intro'   => __( 'Most kitchens and bathrooms move fastest with an in-stock collection. Some spaces need something built to fit. We do both.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--2 zeus-two-paths">
		<article class="zeus-card zeus-two-paths__card">
			<div class="zeus-card__media">
				<?php echo wp_get_attachment_image( 118, 'zeus-card', false, array( 'alt' => 'Brooklyn Pearl in-stock kitchen cabinets' ) ); ?>
			</div>
			<div class="zeus-card__body">
				<h3><?php esc_html_e( 'In-Stock Cabinetry', 'zeus' ); ?></h3>
				<p><?php esc_html_e( 'For kitchens and bathrooms where speed, selection, and value matter — a wide range of styles and finishes, delivered and professionally installed.', 'zeus' ); ?></p>
				<a class="zeus-btn zeus-btn--secondary" href="<?php echo esc_url( $zeus_page_kitchen ? get_permalink( $zeus_page_kitchen ) : home_url( '/cabinets/kitchen-cabinets/' ) ); ?>"><?php esc_html_e( 'Explore In-Stock Collections', 'zeus' ); ?></a>
			</div>
		</article>
		<article class="zeus-card zeus-two-paths__card">
			<div class="zeus-card__media">
				<?php echo wp_get_attachment_image( 139, 'zeus-card', false, array( 'alt' => 'White oak floating shelves, an example of custom cabinetry' ) ); ?>
			</div>
			<div class="zeus-card__body">
				<h3><?php esc_html_e( 'Custom Cabinetry', 'zeus' ); ?></h3>
				<p><?php esc_html_e( 'For closets, built-ins, laundry rooms, pantries, home offices, and non-standard dimensions — spaces that need an individual solution instead of a stock size.', 'zeus' ); ?></p>
				<a class="zeus-btn zeus-btn--secondary" href="<?php echo esc_url( $zeus_page_custom_spaces ? get_permalink( $zeus_page_custom_spaces ) : home_url( '/custom-spaces/' ) ); ?>"><?php esc_html_e( 'Explore Custom Cabinetry', 'zeus' ); ?></a>
			</div>
		</article>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. Featured cabinet collections -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'In-Stock Cabinet Collections', 'zeus' ),
		'heading' => __( 'Popular Styles, Ready to Move', 'zeus' ),
		'intro'   => __( 'From transitional Brooklyn to Slim Shaker Oslo — including OSLO Classic Walnut — these collections are stocked for fast turnaround.', 'zeus' ),
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

<!-- 5. Why in-stock matters -->
<?php
zeus_section_start(
	array(
		'variant' => 'navy',
		'eyebrow' => __( 'Why In-Stock Matters', 'zeus' ),
		'heading' => __( 'Cabinets In Stock. Projects Moving Faster.', 'zeus' ),
	)
);
?>
	<p class="zeus-door-strip__note"><?php esc_html_e( 'A few of our popular in-stock styles and finishes — explore the full collection.', 'zeus' ); ?></p>
	<div class="zeus-door-strip">
		<?php
		$zeus_instock_doors = array(
			100 => __( 'Brooklyn White', 'zeus' ),
			103 => __( 'Shaker White', 'zeus' ),
			107 => __( 'Oslo White', 'zeus' ),
			99  => __( 'Brooklyn Midnight', 'zeus' ),
			106 => __( 'Shaker Moss', 'zeus' ),
			109 => __( 'Oslo Walnut', 'zeus' ),
		);
		foreach ( $zeus_instock_doors as $zeus_door_id => $zeus_door_label ) :
			?>
			<div class="zeus-door-strip__item">
				<?php echo wp_get_attachment_image( $zeus_door_id, 'zeus-square' ); ?>
				<span class="zeus-door-strip__label"><?php echo esc_html( $zeus_door_label ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
	<p style="margin-top: var(--wp--preset--spacing--3);">
		<a class="zeus-btn zeus-btn--secondary zeus-btn--on-dark" href="<?php echo esc_url( home_url( '/cabinet-styles/' ) ); ?>"><?php esc_html_e( 'View All Cabinet Styles', 'zeus' ); ?></a>
	</p>
	<div class="zeus-grid zeus-grid--3" style="margin-top: var(--wp--preset--spacing--5);">
		<div><h3><?php esc_html_e( 'Wide Selection', 'zeus' ); ?></h3><p><?php esc_html_e( 'Multiple styles and finishes available without waiting on a full custom order.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Faster Delivery', 'zeus' ); ?></h3><p><?php esc_html_e( 'In-stock cabinetry moves from selection to delivery faster than made-to-order fabrication.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Professional Installation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Professional delivery and installation coordinated by ZEUS.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 6. Countertops -->
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

<!-- 7. Custom spaces -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Custom Cabinetry', 'zeus' ),
		'heading' => __( 'Built for Spaces That Need an Individual Solution', 'zeus' ),
		'intro'   => __( 'Closets, laundry rooms, pantries, home offices, and floating shelves — custom-built when a standard size or stocked style is not enough.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--3 zeus-grid--align-start">
		<?php
		get_template_part( 'components/card-service', null, array(
			'title' => __( 'Custom Closets', 'zeus' ),
			'desc'  => __( 'Reach-in and walk-in storage systems built around what you actually own.', 'zeus' ),
			'url'   => $zeus_page_closets ? get_permalink( $zeus_page_closets ) : home_url( '/custom-spaces/closets/' ),
		) );
		get_template_part( 'components/card-service', null, array(
			'title' => __( 'Laundry & Pantry', 'zeus' ),
			'desc'  => __( 'Built-in storage planned around your appliances and everyday routine.', 'zeus' ),
			'url'   => $zeus_page_laundry ? get_permalink( $zeus_page_laundry ) : home_url( '/custom-spaces/laundry-pantry/' ),
		) );
		get_template_part( 'components/card-service', null, array(
			'title'    => __( 'Home Office', 'zeus' ),
			'desc'     => __( 'A dedicated, organized workspace built around how you work.', 'zeus' ),
			'url'      => $zeus_page_home_office ? get_permalink( $zeus_page_home_office ) : home_url( '/custom-spaces/home-office/' ),
			'image_id' => 120,
		) );
		?>
	</div>
	<p class="zeus-card__meta" style="margin-top: var(--wp--preset--spacing--4);"><?php esc_html_e( 'Custom floating shelves — another example of the built-in work we do beyond cabinetry:', 'zeus' ); ?></p>
	<div class="zeus-grid zeus-grid--3" style="margin-top: var(--wp--preset--spacing--2);">
		<?php
		$zeus_shelf_labels = array(
			139 => __( 'White Oak', 'zeus' ),
			140 => __( 'Maple', 'zeus' ),
			141 => __( 'Walnut', 'zeus' ),
		);
		foreach ( $zeus_shelf_labels as $zeus_shelf_id => $zeus_shelf_label ) :
			?>
			<div class="zeus-floating-shelf">
				<?php echo wp_get_attachment_image( $zeus_shelf_id, 'zeus-card' ); ?>
				<span class="zeus-floating-shelf__label"><?php echo esc_html( $zeus_shelf_label ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
<?php zeus_section_end(); ?>

<!-- 8. Process -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'Process', 'zeus' ),
		'heading' => __( 'How It Works', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3><p><?php esc_html_e( 'We talk through your space, goals, and budget.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Design & Selection', 'zeus' ); ?></h3><p><?php esc_html_e( 'We plan the layout and help you choose an in-stock collection or a custom-built solution.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Delivery or Build', 'zeus' ); ?></h3><p><?php esc_html_e( 'In-stock cabinetry ships from our warehouse; custom cabinetry is built to your exact spec.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Install', 'zeus' ); ?></h3><p><?php esc_html_e( 'We install and finish the space, ready to use.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 9. Featured real projects — only rendered once real, confidently-
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

<!-- 9b. Real ZEUS project photography — individually-verified-real
     photos, used generically (not attributed to a specific named
     project since grouping could not be confirmed). See
     docs/ASSET-PROVENANCE.csv. -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Real ZEUS Work', 'zeus' ),
		'heading' => __( 'From Real ZEUS Installations', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--3">
		<?php foreach ( array( 74, 75, 77 ) as $zeus_real_photo_id ) : ?>
			<div class="zeus-real-photo">
				<?php echo wp_get_attachment_image( $zeus_real_photo_id, 'zeus-card' ); ?>
				<span class="zeus-real-photo__label"><?php esc_html_e( 'Real ZEUS Installation', 'zeus' ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
<?php zeus_section_end(); ?>

<!-- 10. Why ZEUS -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'Why ZEUS', 'zeus' ),
		'heading' => __( 'Why Homeowners Choose ZEUS', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-why-zeus">
		<div class="zeus-why-zeus__item"><?php echo zeus_icon( 'check' ); // phpcs:ignore ?><h3><?php esc_html_e( 'In Stock or Custom', 'zeus' ); ?></h3><p><?php esc_html_e( 'Popular styles ship fast from our warehouse; custom cabinetry covers everything else.', 'zeus' ); ?></p></div>
		<div class="zeus-why-zeus__item"><?php echo zeus_icon( 'check' ); // phpcs:ignore ?><h3><?php esc_html_e( 'Coordinated Start to Finish', 'zeus' ); ?></h3><p><?php esc_html_e( 'Design, cabinetry, and countertops planned and coordinated together by ZEUS.', 'zeus' ); ?></p></div>
		<div class="zeus-why-zeus__item"><?php echo zeus_icon( 'check' ); // phpcs:ignore ?><h3><?php esc_html_e( 'Clear Pricing Up Front', 'zeus' ); ?></h3><p><?php esc_html_e( 'You get a clear estimate before work begins, so there are no surprises on the final invoice.', 'zeus' ); ?></p></div>
		<div class="zeus-why-zeus__item"><?php echo zeus_icon( 'check' ); // phpcs:ignore ?><h3><?php esc_html_e( 'Local to Central Florida', 'zeus' ); ?></h3><p><?php esc_html_e( 'Focused on the Orlando area rather than spread across the whole state.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 11. Reviews — hidden for now (RC3A). ZEUS has a real, active Google
     Business Profile with an existing reviews feed (see
     docs/OLD-SITE-INVENTORY.md, "Existing review integration"), but no
     verified, clickable review destination (Place ID / direct link) has
     been confirmed for this rebuild yet, and the section must never say
     development/status language like "check back soon" on the public
     site. Per the RC3A brief: hide entirely rather than show placeholder
     copy or fabricate a destination. Restore once the owner supplies the
     Google Business Profile connection details — see
     docs/CONTENT-MIGRATION-PLAN.md. No AggregateRating schema is added
     until real review data is actually connected. -->
<?php // Reviews section intentionally omitted — see comment above. ?>

<!-- 12. Service area -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Service Area', 'zeus' ),
		'heading' => __( 'Orlando & Surrounding Communities', 'zeus' ),
		'intro'   => __( 'Serving homeowners throughout Orlando and communities within approximately 40 miles.', 'zeus' ),
	)
);
$zeus_service_groups = array(
	__( 'Orlando', 'zeus' )            => array( 'Orlando', 'Dr. Phillips', 'Lake Nona', 'Hunters Creek', 'Baldwin Park', 'College Park', 'East Orlando / Avalon Park' ),
	__( 'West / Southwest', 'zeus' )   => array( 'Windermere', 'Horizon West', 'Winter Garden', 'Ocoee', 'Gotha', 'Oakland', 'Clermont', 'Minneola', 'Montverde', 'Celebration' ),
	__( 'North / Northeast', 'zeus' )  => array( 'Winter Park', 'Maitland', 'Altamonte Springs', 'Longwood', 'Lake Mary', 'Sanford', 'Apopka', 'Casselberry', 'Winter Springs', 'Oviedo', 'Eatonville' ),
	__( 'South / Southeast', 'zeus' )  => array( 'Kissimmee', 'St. Cloud', 'Davenport', 'ChampionsGate' ),
);
?>
	<div class="zeus-service-area">
		<?php foreach ( $zeus_service_groups as $zeus_group_label => $zeus_group_cities ) : ?>
			<div class="zeus-service-area__group">
				<h3><?php echo esc_html( $zeus_group_label ); ?></h3>
				<ul>
					<?php foreach ( $zeus_group_cities as $zeus_city ) : ?>
						<li><?php echo esc_html( $zeus_city ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>
	</div>
	<p class="zeus-service-area__note">
		<?php esc_html_e( "Not sure if you're in our service area?", 'zeus' ); ?>
		<a href="<?php echo esc_url( zeus_phone_number_href() ); ?>"><?php esc_html_e( 'Call or text us.', 'zeus' ); ?></a>
	</p>
<?php zeus_section_end(); ?>

<!-- 13. FAQ -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone compact',
		'eyebrow' => __( 'FAQ', 'zeus' ),
		'heading' => __( 'Frequently Asked Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'Is ZEUS cabinetry custom-made or in stock?', 'zeus' ),
		'a' => __( 'Both. We stock popular styles and finishes through a central warehouse for fast turnaround, and we also build custom cabinetry and furniture for closets, built-ins, and spaces that need an individual solution.', 'zeus' ),
	),
	array(
		'q' => __( 'Do you have a showroom I can visit?', 'zeus' ),
		'a' => __( 'ZEUS is a service-area business — we meet with you for a consultation rather than operating a public walk-in showroom.', 'zeus' ),
	),
	array(
		'q' => __( 'What areas do you serve?', 'zeus' ),
		'a' => __( 'Orlando and surrounding communities within approximately 40 miles — including Windermere, Winter Garden, Winter Park, Lake Nona, and Kissimmee. See the Service Area section above for the full list, or call or text us if you\'re not sure.', 'zeus' ),
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

<!-- 14. Request Free Consultation CTA/form -->
<?php
zeus_section_start(
	array(
		'variant'   => 'compact',
		'eyebrow'   => __( 'Get Started', 'zeus' ),
		'heading'   => __( 'Request Your Free Consultation', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
