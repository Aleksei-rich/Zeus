<?php
/**
 * Cabinets hub (/cabinets/) -- RC4F.
 * Auto-selected by WordPress's page-{slug}.php template hierarchy for
 * the "cabinets" page (ID 9); no post-meta template assignment needed.
 * Replaces the previous thin, generic page.php-rendered content --
 * matches the premium hub pattern already established by the Countertops
 * and Cabinet Styles hubs. Connects Kitchen Cabinets, Bathroom Cabinets
 * & Vanities, and Cabinet Styles (in-stock) with Custom Spaces (custom
 * cabinetry), and cross-sells Countertops.
 *
 * No "same team / no subcontractors" claim, no exact lead-time promise,
 * no "stocked locally in Orlando" claim -- see docs/DECISIONS.md
 * ("RC4F"). Real ZEUS section uses only independently-verified real
 * completed-project photos (74, 75, 76, 77), never attributed to a
 * named client/city/date/material/collection beyond what's already
 * documented in docs/ASSET-PROVENANCE.csv.
 */
get_header();
zeus_render_breadcrumbs();

$zeus_page_kitchen       = zeus_get_post_by_slug( 'kitchen-cabinets', 'page' );
$zeus_page_bath          = zeus_get_post_by_slug( 'bathroom-cabinets-vanities', 'page' );
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

// Hero: Oslo Walnut Bathroom (attachment 137) -- premium, wide,
// cabinetry-dominant, and not used as a hero anywhere else on the site.
// Provisional pick from a 4-candidate contact sheet; see
// docs/DECISIONS.md ("RC4F").
$zeus_hero_image_id = 137;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Cabinets in Orlando', 'zeus' ); ?>">
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
					'alt'           => __( 'OSLO Classic Walnut bathroom vanity cabinetry with a marble backsplash', 'zeus' ),
				)
			);
			?>
		<?php endif; ?>
		<div class="zeus-hero__scrim" aria-hidden="true"></div>
	</div>
	<div class="zeus-container">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Cabinets', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Cabinets in Orlando', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'In-stock cabinet collections and fully custom cabinetry, with selection, design, delivery and professional installation coordinated through ZEUS.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Explore Cabinet Options', 'zeus' ), 'url' => '#zeus-cabinet-options', 'variant' => 'secondary', 'on_dark' => true ) ); ?>
			</div>
		</div>
	</div>
</section>

<!-- 2. Two ways to build -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'In-Stock + Custom Cabinetry', 'zeus' ),
		'heading' => __( 'Two Ways to Build Your Cabinetry', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--2 zeus-two-paths">
		<article class="zeus-card zeus-two-paths__card">
			<div class="zeus-card__media">
				<?php echo wp_get_attachment_image( 127, 'zeus-card', false, array( 'alt' => 'Shaker Kodiak in-stock kitchen cabinets' ) ); ?>
			</div>
			<div class="zeus-card__body">
				<h3><?php esc_html_e( 'In-Stock Cabinet Collections', 'zeus' ); ?></h3>
				<p><?php esc_html_e( 'Popular styles and finishes available through our central warehouse, helping projects move from selection to installation efficiently — Shaker, Slim Shaker Oslo, Brooklyn, and Euro / Flat Panel.', 'zeus' ); ?></p>
				<a class="zeus-btn zeus-btn--secondary" href="<?php echo esc_url( home_url( '/cabinet-styles/' ) ); ?>"><?php esc_html_e( 'Explore Cabinet Styles', 'zeus' ); ?></a>
			</div>
		</article>
		<article class="zeus-card zeus-two-paths__card">
			<div class="zeus-card__media">
				<?php echo wp_get_attachment_image( 139, 'zeus-card', false, array( 'alt' => 'White oak floating shelves, an example of custom cabinetry' ) ); ?>
			</div>
			<div class="zeus-card__body">
				<h3><?php esc_html_e( 'Custom Cabinetry', 'zeus' ); ?></h3>
				<p><?php esc_html_e( 'Designed around individual dimensions and project needs, for spaces where standard collection sizing isn\'t enough — closets, built-ins, laundry rooms, pantries, and home offices.', 'zeus' ); ?></p>
				<a class="zeus-btn zeus-btn--secondary" href="<?php echo esc_url( $zeus_page_custom_spaces ? get_permalink( $zeus_page_custom_spaces ) : home_url( '/custom-spaces/' ) ); ?>"><?php esc_html_e( 'Explore Custom Spaces', 'zeus' ); ?></a>
			</div>
		</article>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. Cabinet project types -->
<?php
zeus_section_start(
	array(
		'id'      => 'zeus-cabinet-options',
		'variant' => 'stone',
		'eyebrow' => __( 'Where You Need Cabinetry', 'zeus' ),
		'heading' => __( 'Cabinet Project Types', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--3">
		<?php
		get_template_part( 'components/card-service', null, array(
			'title'    => __( 'Kitchen Cabinets', 'zeus' ),
			'desc'     => __( 'Custom kitchen cabinets planned around how your kitchen is actually used.', 'zeus' ),
			'url'      => $zeus_page_kitchen ? get_permalink( $zeus_page_kitchen ) : home_url( '/cabinets/kitchen-cabinets/' ),
			'image_id' => 114,
		) );
		get_template_part( 'components/card-service', null, array(
			'title'    => __( 'Bathroom Cabinets & Vanities', 'zeus' ),
			'desc'     => __( 'Vanities and storage sized and finished for your space, from a single vanity to a full remodel.', 'zeus' ),
			'url'      => $zeus_page_bath ? get_permalink( $zeus_page_bath ) : home_url( '/cabinets/bathroom-cabinets-vanities/' ),
			'image_id' => 119,
		) );
		get_template_part( 'components/card-service', null, array(
			'title'    => __( 'Cabinet Styles', 'zeus' ),
			'desc'     => __( 'Compare Brooklyn, Shaker, Slim Shaker Oslo, and Euro / Flat Panel — colors, finishes and door profiles.', 'zeus' ),
			'url'      => home_url( '/cabinet-styles/' ),
			'image_id' => 118,
		) );
		?>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. Popular cabinet styles -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'In-Stock Cabinet Collections', 'zeus' ),
		'heading' => __( 'Popular Cabinet Styles', 'zeus' ),
		'intro'   => __( 'From transitional Brooklyn to Slim Shaker Oslo — including OSLO Classic Walnut — these collections are stocked for efficient turnaround.', 'zeus' ),
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

<!-- 5. Why in-stock cabinetry -->
<?php
zeus_section_start(
	array(
		'variant' => 'navy',
		'eyebrow' => __( 'Why In-Stock Matters', 'zeus' ),
		'heading' => __( 'Why In-Stock Cabinetry', 'zeus' ),
		'intro'   => __( 'Established finishes and styles, available through our central warehouse, make selection easier and help projects move faster than a fully custom build.', 'zeus' ),
	)
);
?>
	<div class="zeus-door-strip">
		<?php
		$zeus_instock_doors = array(
			100 => __( 'Brooklyn White', 'zeus' ),
			103 => __( 'Shaker White', 'zeus' ),
			107 => __( 'Oslo White', 'zeus' ),
			104 => __( 'Shaker Sand', 'zeus' ),
			99  => __( 'Brooklyn Midnight', 'zeus' ),
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
	<div class="zeus-grid zeus-grid--3" style="margin-top: var(--wp--preset--spacing--5);">
		<div><h3><?php esc_html_e( 'Easier Selection', 'zeus' ); ?></h3><p><?php esc_html_e( 'Established finishes and styles make comparing options straightforward.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Central Warehouse', 'zeus' ); ?></h3><p><?php esc_html_e( 'Popular collections are available through our central warehouse.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Faster Turnaround', 'zeus' ); ?></h3><p><?php esc_html_e( 'Generally quicker than a fully custom fabrication timeline.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 6. Custom cabinetry -->
<?php zeus_section_start( array( 'variant' => 'compact' ) ); ?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<div>
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Custom Cabinetry', 'zeus' ); ?></p>
			<h2><?php esc_html_e( 'When Custom Cabinetry Makes Sense', 'zeus' ); ?></h2>
			<p><?php esc_html_e( 'Unusual dimensions, built-ins, closets, laundry rooms and pantries, home offices — projects that need an individual solution rather than a standard collection size or configuration.', 'zeus' ); ?></p>
			<a class="zeus-btn zeus-btn--secondary" href="<?php echo esc_url( $zeus_page_custom_spaces ? get_permalink( $zeus_page_custom_spaces ) : home_url( '/custom-spaces/' ) ); ?>"><?php esc_html_e( 'Explore Custom Spaces', 'zeus' ); ?></a>
		</div>
		<div>
			<?php echo wp_get_attachment_image( 112, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;', 'alt' => 'Custom built-in home office cabinetry with a two-person desk' ) ); ?>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 7. Cabinets + countertops -->
<?php zeus_section_start( array( 'variant' => 'stone' ) ); ?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<div>
			<?php echo wp_get_attachment_image( 156, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;', 'alt' => 'Modern kitchen with light quartz countertop and waterfall island' ) ); ?>
		</div>
		<div>
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Countertops', 'zeus' ); ?></p>
			<h2><?php esc_html_e( 'Cabinets & Countertops, Coordinated Together', 'zeus' ); ?></h2>
			<p><?php esc_html_e( 'Your countertop material affects the whole room, so ZEUS plans cabinetry and countertop selection as one project — quartz, granite, porcelain, and marble alongside your cabinets.', 'zeus' ); ?></p>
			<a class="zeus-btn zeus-btn--secondary" href="<?php echo esc_url( $zeus_page_countertops ? get_permalink( $zeus_page_countertops ) : home_url( '/countertops/' ) ); ?>"><?php esc_html_e( 'Explore Countertops', 'zeus' ); ?></a>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 8. Process -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Process', 'zeus' ),
		'heading' => __( 'How the Cabinetry Process Works', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Discuss your project, spaces, and whether in-stock or custom cabinetry fits best.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Measurements & Planning', 'zeus' ); ?></h3><p><?php esc_html_e( 'Your space is measured and the layout planned around it.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Selections / Design', 'zeus' ); ?></h3><p><?php esc_html_e( 'Choose a style and finish, or finalize a custom design.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Delivery & Installation Coordination', 'zeus' ); ?></h3><p><?php esc_html_e( 'Delivery and professional installation coordinated by ZEUS.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 9. Real ZEUS work -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Real ZEUS Work', 'zeus' ),
		'heading' => __( 'Real ZEUS Cabinetry Installations', 'zeus' ),
		'intro'   => __( 'Actual installation photos from ZEUS projects.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<?php foreach ( array( 75, 74, 76, 77 ) as $zeus_real_photo_id ) : ?>
			<div class="zeus-real-photo">
				<?php echo wp_get_attachment_image( $zeus_real_photo_id, 'zeus-card' ); ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php zeus_section_end(); ?>

<!-- 10. Service area -->
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
			'ZEUS designs, selects, and installs kitchen, bathroom, and custom cabinetry for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.',
			'zeus'
		);
		?>
	</p>
	<p class="zeus-service-area__note">
		<?php esc_html_e( "Not sure if you're in our service area?", 'zeus' ); ?>
		<a href="<?php echo esc_url( zeus_phone_number_href() ); ?>"><?php esc_html_e( 'Call or text us.', 'zeus' ); ?></a>
	</p>
<?php zeus_section_end(); ?>

<!-- 11. FAQ -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'FAQ', 'zeus' ),
		'heading' => __( 'Cabinet Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'What is the difference between in-stock and custom cabinetry?', 'zeus' ),
		'a' => __( 'In-stock collections are popular styles and finishes available through our central warehouse, which generally means a faster turnaround. Custom cabinetry is designed around your specific dimensions and needs, for spaces a standard collection can\'t fit.', 'zeus' ),
	),
	array(
		'q' => __( 'Do you build cabinets for both kitchens and bathrooms?', 'zeus' ),
		'a' => __( 'Yes — see our Kitchen Cabinets and Bathroom Cabinets & Vanities pages for details specific to each room.', 'zeus' ),
	),
	array(
		'q' => __( 'What cabinet styles are available?', 'zeus' ),
		'a' => __( 'Brooklyn, Shaker, Slim Shaker Oslo (including OSLO Classic Walnut), and Euro / Flat Panel — visit Cabinet Styles to compare door profiles, colors and finishes.', 'zeus' ),
	),
	array(
		'q' => __( 'How are measurements handled?', 'zeus' ),
		'a' => __( 'We measure your space as part of the planning process, before finalizing a layout or placing an order.', 'zeus' ),
	),
	array(
		'q' => __( 'Do you coordinate delivery and installation?', 'zeus' ),
		'a' => __( 'Yes — delivery and professional installation are coordinated by ZEUS as part of your project.', 'zeus' ),
	),
	array(
		'q' => __( 'Can ZEUS coordinate countertops with my cabinets?', 'zeus' ),
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

<!-- 12. Final CTA / consultation form -->
<?php
zeus_section_start(
	array(
		'variant'   => 'compact',
		'eyebrow'   => __( 'Get Started', 'zeus' ),
		'heading'   => __( 'Ready to Plan Your Cabinetry?', 'zeus' ),
		'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
