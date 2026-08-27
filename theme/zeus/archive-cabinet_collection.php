<?php
/**
 * Cabinet Styles hub (/cabinet-styles/) -- RC4E.
 * Post-type archive template for `cabinet_collection` (WordPress's
 * archive-{post_type}.php template hierarchy). Routes to the four
 * collection detail pages (Brooklyn, Shaker, Oslo, Euro / Flat Panel),
 * all of which render via single-cabinet_collection.php.
 *
 * Positioning: a practical cabinet-style catalog, not a generic SEO
 * article -- explains what each door style looks like, how the styles
 * differ, and that popular in-stock collections (fast turnaround via a
 * central warehouse) and fully custom cabinetry are complementary ZEUS
 * services. No brittle promises (exact delivery days, guaranteed
 * same-day stock, "stocked locally in Orlando") -- see docs/DECISIONS.md
 * ("RC4E") and docs/ASSET-PROVENANCE.csv. All collection imagery is
 * dealer-provided lifestyle/product media or (Euro only) generated
 * category/lifestyle visuals -- never presented as a completed ZEUS
 * project. No "Real ZEUS Work" section: no independently-verified real
 * ZEUS installation photo is mapped to a specific collection/finish.
 */
get_header();
zeus_render_breadcrumbs();

$zeus_collections = get_posts(
	array(
		'post_type'      => 'cabinet_collection',
		'posts_per_page' => 4,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	)
);
$zeus_page_custom_spaces = zeus_get_post_by_slug( 'custom-spaces', 'page' );
$zeus_page_kitchen       = zeus_get_post_by_slug( 'kitchen-cabinets', 'page' );

// Hero: Brooklyn Pearl Kitchen (attachment 118) -- premium, broadly
// representative cabinetry lifestyle image, chosen after the owner
// rejected attachment 124 (read as inexpensive/builder-grade). See
// docs/DECISIONS.md ("RC4E hero polish").
$zeus_hero_image_id = 118;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Cabinet Styles in Orlando', 'zeus' ); ?>">
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
					'alt'           => __( 'Brooklyn Pearl kitchen cabinets with marble countertops and a walnut range hood', 'zeus' ),
				)
			);
			?>
		<?php endif; ?>
		<div class="zeus-hero__scrim" aria-hidden="true"></div>
	</div>
	<div class="zeus-container">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Cabinet Styles', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Cabinet Styles in Orlando', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'Compare framed Shaker, modern Slim Shaker and clean Euro flat-panel cabinetry, then explore available collections, colors and finishes.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Explore Cabinet Styles', 'zeus' ), 'url' => '#zeus-styles', 'variant' => 'secondary', 'on_dark' => true ) ); ?>
			</div>
		</div>
	</div>
</section>

<!-- 2. Style paths -->
<?php
zeus_section_start(
	array(
		'id'      => 'zeus-styles',
		'eyebrow' => __( 'Styles', 'zeus' ),
		'heading' => __( 'Four Cabinet Styles', 'zeus' ),
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

<!-- 3. Style comparison -->
<?php
zeus_section_start(
	array(
		'id'      => 'zeus-compare',
		'variant' => 'stone',
		'eyebrow' => __( 'Compare', 'zeus' ),
		'heading' => __( 'Which Cabinet Style Fits Your Home?', 'zeus' ),
		'intro'   => __( 'A practical side-by-side to help you tell Standard Shaker, Slim Shaker and Flat Panel apart at a glance -- we\'ll go deeper on doors, finishes and hardware during your consultation.', 'zeus' ),
	)
);
?>
	<div class="zeus-compare-wrap">
		<table class="zeus-compare-table">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Category', 'zeus' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Brooklyn', 'zeus' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Shaker', 'zeus' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Oslo (Slim Shaker)', 'zeus' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Euro / Flat Panel', 'zeus' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Door profile', 'zeus' ); ?></th>
					<td><?php esc_html_e( 'Full-overlay, minimal visible frame between doors', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Classic five-piece recessed-panel frame', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Same recessed-panel geometry, narrower frame', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'No frame at all -- a single flat slab front', 'zeus' ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Overall character', 'zeus' ); ?></th>
					<td><?php esc_html_e( 'Clean, transitional -- works modern or classic', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Classic and versatile', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Cleaner and more architectural than traditional Shaker', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Contemporary, minimal, architectural', 'zeus' ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Visual detail', 'zeus' ); ?></th>
					<td><?php esc_html_e( 'Mostly door front, minimal reveal lines', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Visible frame and recessed center panel', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Visible frame, narrower rail width', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'No raised or recessed detail -- uninterrupted face', 'zeus' ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Typical design direction', 'zeus' ); ?></th>
					<td><?php esc_html_e( 'Modern and classic kitchens and baths', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Traditional, transitional and many modern interiors', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Modern, streamlined interiors', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Modern and minimal interiors, slab countertops', 'zeus' ); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. In-stock collections -->
<?php
zeus_section_start(
	array(
		'variant' => 'navy',
		'eyebrow' => __( 'In-Stock', 'zeus' ),
		'heading' => __( 'In-Stock Cabinet Collections', 'zeus' ),
		'intro'   => __( 'Popular styles and finishes are available through our central warehouse, helping ZEUS move projects from selection to installation efficiently.', 'zeus' ),
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
	<p style="margin-top: var(--wp--preset--spacing--3); color: rgba(255,255,255,0.85);">
		<?php esc_html_e( 'A few of our popular in-stock styles and finishes, from light and white to warm neutral and darker statement tones -- explore each collection below for the full color range.', 'zeus' ); ?>
	</p>
<?php zeus_section_end(); ?>

<!-- 5. Custom cabinetry path -->
<?php zeus_section_start( array( 'variant' => 'compact' ) ); ?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<div>
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Custom Cabinetry', 'zeus' ); ?></p>
			<h2><?php esc_html_e( 'Need Something Beyond a Standard Collection?', 'zeus' ); ?></h2>
			<p><?php esc_html_e( 'For unusual dimensions, built-ins, or a fully custom design, ZEUS can create custom cabinetry built around your room and project requirements. In-stock collections and custom cabinetry are complementary ZEUS services -- many projects use both.', 'zeus' ); ?></p>
			<p>
				<?php
				printf(
					/* translators: 1: link to Custom Spaces, 2: link to Kitchen Cabinets */
					wp_kses_post( __( 'See our %1$s, or start with %2$s.', 'zeus' ) ),
					'<a href="' . esc_url( $zeus_page_custom_spaces ? get_permalink( $zeus_page_custom_spaces ) : home_url( '/custom-spaces/' ) ) . '">' . esc_html__( 'custom cabinetry for non-standard spaces', 'zeus' ) . '</a>',
					'<a href="' . esc_url( $zeus_page_kitchen ? get_permalink( $zeus_page_kitchen ) : home_url( '/cabinets/kitchen-cabinets/' ) ) . '">' . esc_html__( 'kitchen cabinets', 'zeus' ) . '</a>'
				);
				?>
			</p>
		</div>
		<div>
			<?php echo wp_get_attachment_image( 139, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;', 'alt' => 'White oak floating shelves, an example of custom cabinetry' ) ); ?>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 6. Process -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Process', 'zeus' ),
		'heading' => __( 'From Style to Installation', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Choose a Style', 'zeus' ); ?></h3><p><?php esc_html_e( 'Compare Brooklyn, Shaker, Oslo and Euro / Flat Panel.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Select Color / Finish', 'zeus' ); ?></h3><p><?php esc_html_e( 'Choose from each collection\'s available colors and finishes.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Design & Measure', 'zeus' ); ?></h3><p><?php esc_html_e( 'We plan the layout and take precise measurements for your space.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Delivery & Professional Installation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Delivery and installation coordinated by ZEUS.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 7. Service area -->
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
			'ZEUS selects, designs, and installs cabinetry for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.',
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
		'heading' => __( 'Cabinet Style Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'What is the difference between Shaker and Slim Shaker cabinets?', 'zeus' ),
		'a' => __( 'Both use the same five-piece recessed-panel door construction. Traditional Shaker has a more pronounced frame width; our Oslo Slim Shaker collection uses a narrower rail for a cleaner, more architectural look.', 'zeus' ),
	),
	array(
		'q' => __( 'What are flat-panel cabinets?', 'zeus' ),
		'a' => __( 'Flat-panel (Euro-style) cabinets have a single flat door face front to back, with no raised or recessed panel detail -- a streamlined, contemporary look that pairs well with slab countertops.', 'zeus' ),
	),
	array(
		'q' => __( 'Which cabinet colors are available?', 'zeus' ),
		'a' => __( 'It depends on the collection: Brooklyn comes in Fawn, Gray, Midnight, White, Pearl and Slate; Shaker in White, Sand, Kodiak and Moss; Oslo in White, Oak and OSLO Classic Walnut. Visit each collection page for the full range.', 'zeus' ),
	),
	array(
		'q' => __( 'Are white cabinets available?', 'zeus' ),
		'a' => __( 'Yes -- White is available in Brooklyn, Shaker and Oslo, and is one of our most popular in-stock colors.', 'zeus' ),
	),
	array(
		'q' => __( 'Can ZEUS help me choose a cabinet style?', 'zeus' ),
		'a' => __( 'Yes -- we\'ll go over door profiles, colors, and how each style fits your space during your free consultation.', 'zeus' ),
	),
	array(
		'q' => __( 'Do you also make custom cabinets?', 'zeus' ),
		'a' => __( 'Yes -- alongside our in-stock collections, ZEUS designs and builds fully custom cabinetry for non-standard dimensions, built-ins, and individual project needs.', 'zeus' ),
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
		'heading'   => __( 'Find the Right Cabinet Style for Your Project', 'zeus' ),
		'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
