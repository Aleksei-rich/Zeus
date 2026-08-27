<?php
/**
 * Single cabinet collection detail page -- RC4E. Shared template for all
 * four collections (WordPress's single-{post_type}.php template
 * hierarchy applies identically to every `cabinet_collection` post, so
 * per-collection copy/imagery lives in the $zeus_collection_copy array
 * below rather than four separate template files).
 *
 * URLs: /cabinet-styles/brooklyn/, /cabinet-styles/shaker/,
 * /cabinet-styles/oslo/, /cabinet-styles/euro-flat-panel/.
 *
 * All collection imagery is dealer-provided lifestyle/product media
 * (Euro's is generated category/lifestyle visuals) -- never presented
 * as a completed ZEUS project, never in a Real ZEUS/Portfolio/before-
 * after/client-installation context. See docs/ASSET-PROVENANCE.csv and
 * docs/DECISIONS.md ("RC4E"). Finish/color data (names, availability
 * per collection) matches the approved business catalog exactly --
 * Euro / Flat Panel deliberately has no fixed color list, since none is
 * documented, so this template shows design directions instead of
 * finishes for that collection only.
 */
get_header();
zeus_render_breadcrumbs();

while ( have_posts() ) :
	the_post();
	$zeus_id   = get_the_ID();
	$zeus_slug = get_post_field( 'post_name', $zeus_id );

	$zeus_notes       = get_post_meta( $zeus_id, 'zeus_construction_notes', true );
	$zeus_gallery_ids = zeus_get_gallery_ids( $zeus_id );
	$zeus_swatches    = get_post_meta( $zeus_id, 'zeus_finish_swatches', true );
	$zeus_swatches    = is_array( $zeus_swatches ) ? $zeus_swatches : array();

	$zeus_page_kitchen = zeus_get_post_by_slug( 'kitchen-cabinets', 'page' );
	$zeus_page_bath    = zeus_get_post_by_slug( 'bathroom-cabinets-vanities', 'page' );
	$zeus_page_home_office = zeus_get_post_by_slug( 'home-office', 'page' );

	/**
	 * Per-collection copy, imagery, and finish ordering. White (where
	 * available) is always listed first so it is never buried at the
	 * end of an unstructured gallery -- see docs/DECISIONS.md ("RC4E").
	 */
	$zeus_collection_copy = array(
		'brooklyn'        => array(
			'eyebrow'      => __( 'Brooklyn Collection', 'zeus' ),
			'h1'           => __( 'Brooklyn Cabinet Collection', 'zeus' ),
			'lede'         => __( 'A full-overlay, transitional cabinet collection — doors cover most of the cabinet\'s face frame for a clean, minimal, continuous line across a run of cabinetry.', 'zeus' ),
			'hero_id'      => 110,
			'hero_alt'     => __( 'Brooklyn Fawn two-tone kitchen cabinets with a light oak island', 'zeus' ),
			'finish_order' => array( 'White', 'Pearl', 'Fawn', 'Gray', 'Slate', 'Midnight' ),
		),
		'shaker'          => array(
			'eyebrow'      => __( 'Shaker Cabinets', 'zeus' ),
			'h1'           => __( 'Shaker Cabinets in Orlando', 'zeus' ),
			'lede'         => __( 'A framed, five-piece recessed-panel cabinet style with a clean, versatile look that works across traditional, transitional and modern interiors.', 'zeus' ),
			'hero_id'      => 129,
			'hero_alt'     => __( 'Shaker Moss kitchen cabinets with a marble slab backsplash and wood range hood', 'zeus' ),
			'finish_order' => array( 'White', 'Sand', 'Kodiak', 'Moss' ),
		),
		'oslo'            => array(
			'eyebrow'      => __( 'Oslo — Slim Shaker', 'zeus' ),
			'h1'           => __( 'Oslo Slim Shaker Cabinets in Orlando', 'zeus' ),
			'lede'         => __( 'A modern framed cabinet style with a slimmer visual profile than traditional Shaker cabinetry.', 'zeus' ),
			'hero_id'      => 136,
			'hero_alt'     => __( 'OSLO Classic Walnut Slim Shaker kitchen cabinets', 'zeus' ),
			'finish_order' => array( 'White', 'Oak', 'Walnut' ),
		),
		'euro-flat-panel' => array(
			'eyebrow'      => __( 'Euro / Flat Panel', 'zeus' ),
			'h1'           => __( 'Euro Flat Panel Cabinets in Orlando', 'zeus' ),
			'lede'         => __( 'A clean slab-front cabinet style with minimal visual lines and a contemporary architectural look.', 'zeus' ),
			'hero_id'      => 153,
			'hero_alt'     => __( 'Modern Euro flat panel kitchen cabinetry in warm white and walnut', 'zeus' ),
			'finish_order' => array(),
		),
	);
	$zeus_copy = $zeus_collection_copy[ $zeus_slug ] ?? array(
		'eyebrow'      => get_post_meta( $zeus_id, 'zeus_profile_type', true ),
		'h1'           => get_the_title(),
		'lede'         => get_the_excerpt(),
		'hero_id'      => get_post_thumbnail_id(),
		'hero_alt'     => get_the_title() . ' cabinets',
		'finish_order' => array(),
	);

	$zeus_ordered_finishes = array();
	foreach ( $zeus_copy['finish_order'] as $zeus_finish_name ) {
		$zeus_term = get_term_by( 'name', $zeus_finish_name, 'finish' );
		if ( $zeus_term ) {
			$zeus_ordered_finishes[] = $zeus_term;
		}
	}
	?>

	<!-- 1. Hero -->
	<section class="zeus-hero" aria-label="<?php echo esc_attr( $zeus_copy['h1'] ); ?>">
		<div class="zeus-hero__media">
			<?php if ( ! empty( $zeus_copy['hero_id'] ) ) : ?>
				<?php
				echo wp_get_attachment_image(
					$zeus_copy['hero_id'],
					'zeus-hero',
					false,
					array(
						'loading'       => 'eager',
						'fetchpriority' => 'high',
						'class'         => 'zeus-hero__img',
						'alt'           => $zeus_copy['hero_alt'],
					)
				);
				?>
			<?php endif; ?>
			<div class="zeus-hero__scrim" aria-hidden="true"></div>
		</div>
		<div class="zeus-container">
			<div class="zeus-hero__content">
				<p class="zeus-section__eyebrow"><?php echo esc_html( $zeus_copy['eyebrow'] ); ?></p>
				<h1><?php echo esc_html( $zeus_copy['h1'] ); ?></h1>
				<p class="zeus-hero__lede"><?php echo esc_html( $zeus_copy['lede'] ); ?></p>
				<div class="zeus-cta__actions" style="justify-content:flex-start;">
					<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
					<?php get_template_part( 'components/button', null, array( 'label' => __( 'All Cabinet Styles', 'zeus' ), 'url' => home_url( '/cabinet-styles/' ), 'variant' => 'secondary', 'on_dark' => true ) ); ?>
				</div>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $zeus_ordered_finishes ) ) : ?>
	<!-- 2. Colors / finishes -->
	<?php
	zeus_section_start(
		array(
			'eyebrow' => __( 'Colors', 'zeus' ),
			'heading' => 'oslo' === $zeus_slug ? __( 'Oslo Finishes', 'zeus' ) : ( 'brooklyn' === $zeus_slug ? __( 'Brooklyn Colors', 'zeus' ) : __( 'Shaker Colors', 'zeus' ) ),
		)
	);
	?>
		<div class="zeus-swatch-grid">
			<?php foreach ( $zeus_ordered_finishes as $zeus_finish ) : ?>
				<div class="zeus-swatch<?php echo 'White' === $zeus_finish->name ? ' zeus-swatch--featured' : ''; ?>">
					<?php if ( ! empty( $zeus_swatches[ $zeus_finish->term_id ] ) ) : ?>
						<?php echo wp_get_attachment_image( $zeus_swatches[ $zeus_finish->term_id ], 'zeus-square', false, array( 'class' => 'zeus-swatch__img' ) ); ?>
					<?php endif; ?>
					<span class="zeus-swatch__label">
						<?php
						if ( 'oslo' === $zeus_slug && 'Walnut' === $zeus_finish->name ) {
							esc_html_e( 'OSLO Classic Walnut', 'zeus' );
						} else {
							echo esc_html( $zeus_finish->name );
						}
						?>
					</span>
				</div>
			<?php endforeach; ?>
		</div>
	<?php zeus_section_end(); ?>
	<?php endif; ?>

	<!-- 3. Collection-specific differentiation section -->
	<?php if ( 'brooklyn' === $zeus_slug ) : ?>
		<?php zeus_section_start( array( 'variant' => 'stone', 'eyebrow' => __( 'Design', 'zeus' ), 'heading' => __( 'How the Colors Change the Look', 'zeus' ) ) ); ?>
			<div class="zeus-grid zeus-grid--3">
				<div><h3><?php esc_html_e( 'White & Pearl', 'zeus' ); ?></h3><p><?php esc_html_e( 'Light and open — the most popular choice for bright, airy kitchens and baths.', 'zeus' ); ?></p></div>
				<div><h3><?php esc_html_e( 'Fawn & Gray', 'zeus' ); ?></h3><p><?php esc_html_e( 'A warmer or more neutral direction, without the strong contrast of a darker finish.', 'zeus' ); ?></p></div>
				<div><h3><?php esc_html_e( 'Slate & Midnight', 'zeus' ); ?></h3><p><?php esc_html_e( 'Stronger contrast and statement cabinetry, for homeowners who want a bolder look.', 'zeus' ); ?></p></div>
			</div>
		<?php zeus_section_end(); ?>
	<?php elseif ( 'shaker' === $zeus_slug ) : ?>
		<?php zeus_section_start( array( 'variant' => 'stone', 'eyebrow' => __( 'Profile', 'zeus' ), 'heading' => __( 'What Makes Shaker Different', 'zeus' ) ) ); ?>
			<p>
				<?php
				printf(
					/* translators: %s: link to Oslo */
					wp_kses_post( __( 'Shaker\'s classic five-piece door has a more visible framed profile than our %s collection, which uses the same recessed-panel construction with a narrower rail for a cleaner, more streamlined look. Both are framed, five-piece doors — the difference is the width of that frame.', 'zeus' ) ),
					'<a href="' . esc_url( home_url( '/cabinet-styles/oslo/' ) ) . '">' . esc_html__( 'Oslo Slim Shaker', 'zeus' ) . '</a>'
				);
				?>
			</p>
			<div class="zeus-grid zeus-grid--3" style="margin-top: var(--wp--preset--spacing--3);">
				<div><h3><?php esc_html_e( 'Light & White', 'zeus' ); ?></h3><p><?php esc_html_e( 'White is a popular, versatile choice for bright kitchens and baths.', 'zeus' ); ?></p></div>
				<div><h3><?php esc_html_e( 'Warmer Neutral', 'zeus' ); ?></h3><p><?php esc_html_e( 'Sand brings a softer, warmer neutral tone to the same door profile.', 'zeus' ); ?></p></div>
				<div><h3><?php esc_html_e( 'Darker & Earthier', 'zeus' ); ?></h3><p><?php esc_html_e( 'Kodiak and Moss bring more depth for homeowners who want a richer palette.', 'zeus' ); ?></p></div>
			</div>
		<?php zeus_section_end(); ?>
	<?php elseif ( 'oslo' === $zeus_slug ) : ?>
		<?php zeus_section_start( array( 'variant' => 'stone', 'eyebrow' => __( 'Profile', 'zeus' ), 'heading' => __( 'Slim Shaker vs. Traditional Shaker', 'zeus' ) ) ); ?>
			<div class="zeus-grid zeus-grid--3">
				<div>
					<h3><?php esc_html_e( 'Traditional Shaker', 'zeus' ); ?></h3>
					<p><?php esc_html_e( 'A more pronounced framed door profile.', 'zeus' ); ?></p>
					<p><a href="<?php echo esc_url( home_url( '/cabinet-styles/shaker/' ) ); ?>"><?php esc_html_e( 'See Shaker', 'zeus' ); ?></a></p>
				</div>
				<div>
					<h3><?php esc_html_e( 'Slim Shaker (Oslo)', 'zeus' ); ?></h3>
					<p><?php esc_html_e( 'The same recessed-panel geometry, narrower and cleaner.', 'zeus' ); ?></p>
				</div>
				<div>
					<h3><?php esc_html_e( 'Flat Panel', 'zeus' ); ?></h3>
					<p><?php esc_html_e( 'No framed profile at all — a single flat slab front.', 'zeus' ); ?></p>
					<p><a href="<?php echo esc_url( home_url( '/cabinet-styles/euro-flat-panel/' ) ); ?>"><?php esc_html_e( 'See Euro / Flat Panel', 'zeus' ); ?></a></p>
				</div>
			</div>
		<?php zeus_section_end(); ?>
		<?php zeus_section_start( array( 'eyebrow' => __( 'Design', 'zeus' ), 'heading' => __( 'Design Character', 'zeus' ) ) ); ?>
			<div class="zeus-grid zeus-grid--3">
				<div>
					<?php echo wp_get_attachment_image( 131, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto; margin-bottom: var(--wp--preset--spacing--2);', 'alt' => 'Oslo White Slim Shaker kitchen cabinets' ) ); ?>
					<h3><?php esc_html_e( 'White', 'zeus' ); ?></h3>
					<p><?php esc_html_e( 'A light, modern framed look.', 'zeus' ); ?></p>
				</div>
				<div>
					<?php echo wp_get_attachment_image( 133, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto; margin-bottom: var(--wp--preset--spacing--2);', 'alt' => 'Oslo Oak Slim Shaker kitchen cabinets' ) ); ?>
					<h3><?php esc_html_e( 'Oak', 'zeus' ); ?></h3>
					<p><?php esc_html_e( 'A warmer, natural contemporary direction.', 'zeus' ); ?></p>
				</div>
				<div>
					<?php echo wp_get_attachment_image( 138, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto; margin-bottom: var(--wp--preset--spacing--2);', 'alt' => 'OSLO Classic Walnut Slim Shaker cabinetry in a home bar' ) ); ?>
					<h3><?php esc_html_e( 'OSLO Classic Walnut', 'zeus' ); ?></h3>
					<p><?php esc_html_e( 'A richer, modern built-in / architectural character.', 'zeus' ); ?></p>
				</div>
			</div>
		<?php zeus_section_end(); ?>
	<?php elseif ( 'euro-flat-panel' === $zeus_slug ) : ?>
		<?php zeus_section_start( array( 'variant' => 'stone', 'eyebrow' => __( 'Profile', 'zeus' ), 'heading' => __( 'What Makes Flat Panel Different', 'zeus' ) ) ); ?>
			<div class="zeus-grid zeus-grid--2" style="align-items:center;">
				<div>
					<p><?php esc_html_e( 'Flat-panel doors have no raised or recessed center panel and no framed Shaker profile — a single flat face front to back. That uninterrupted slab appearance pairs naturally with slab countertops and minimal or handle-less hardware, and suits modern, minimal interiors.', 'zeus' ); ?></p>
					<p><?php esc_html_e( 'Not every flat-panel cabinet uses identical construction or materials — we\'ll go over the specific options and hardware together during your consultation.', 'zeus' ); ?></p>
				</div>
				<div>
					<?php echo wp_get_attachment_image( 153, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;', 'alt' => 'Modern Euro flat panel kitchen cabinetry in warm white and walnut' ) ); ?>
				</div>
			</div>
		<?php zeus_section_end(); ?>
		<?php zeus_section_start( array( 'eyebrow' => __( 'Design', 'zeus' ), 'heading' => __( 'Design Directions', 'zeus' ), 'intro' => __( 'These are visual directions, not a fixed list of stocked finishes — we\'ll confirm current options together during your consultation.', 'zeus' ) ) ); ?>
			<div class="zeus-grid zeus-grid--2">
				<div>
					<?php echo wp_get_attachment_image( 164, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto; margin-bottom: var(--wp--preset--spacing--2);', 'alt' => 'Modern home office with light Euro flat-panel built-in cabinetry' ) ); ?>
					<h3><?php esc_html_e( 'Light / White', 'zeus' ); ?></h3>
					<p><?php esc_html_e( 'A light, minimal slab-front look — shown here in a home office built-in.', 'zeus' ); ?></p>
				</div>
				<div>
					<?php echo wp_get_attachment_image( 165, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto; margin-bottom: var(--wp--preset--spacing--2);', 'alt' => 'Two-person home office with warm wood and dark flat-panel built-in cabinetry' ) ); ?>
					<h3><?php esc_html_e( 'Warm Wood / Darker Contrast', 'zeus' ); ?></h3>
					<p><?php esc_html_e( 'A richer, contrasting slab-front look for a more dramatic built-in.', 'zeus' ); ?></p>
				</div>
			</div>
		<?php zeus_section_end(); ?>
		<?php zeus_section_start( array( 'variant' => 'stone', 'eyebrow' => __( 'Compare', 'zeus' ), 'heading' => __( 'Flat Panel or Slim Shaker?', 'zeus' ) ) ); ?>
			<div class="zeus-grid zeus-grid--2">
				<div><h3><?php esc_html_e( 'Flat Panel', 'zeus' ); ?></h3><p><?php esc_html_e( 'A completely slab-front visual — no frame, no lines around the door.', 'zeus' ); ?></p></div>
				<div><h3><?php esc_html_e( 'Slim Shaker (Oslo)', 'zeus' ); ?></h3><p><?php esc_html_e( 'A narrow framed visual — you can see a thin border and recessed center panel on every door.', 'zeus' ); ?></p></div>
			</div>
			<p style="margin-top: var(--wp--preset--spacing--2);"><a href="<?php echo esc_url( home_url( '/cabinet-styles/oslo/' ) ); ?>"><?php esc_html_e( 'Compare with Oslo Slim Shaker', 'zeus' ); ?></a></p>
		<?php zeus_section_end(); ?>
	<?php endif; ?>

	<?php if ( $zeus_notes ) : ?>
	<!-- 4. Material & construction -->
	<?php zeus_section_start( array( 'eyebrow' => __( 'Construction', 'zeus' ), 'heading' => __( 'Material & Construction', 'zeus' ) ) ); ?>
		<p><?php echo wp_kses_post( $zeus_notes ); ?></p>
	<?php zeus_section_end(); ?>
	<?php endif; ?>

	<!-- 5. Applications -->
	<?php zeus_section_start( array( 'variant' => 'stone', 'eyebrow' => __( 'Applications', 'zeus' ), 'heading' => __( 'Where It Works', 'zeus' ) ) ); ?>
		<div class="zeus-grid zeus-grid--4">
			<div><h3><?php esc_html_e( 'Kitchen Cabinets', 'zeus' ); ?></h3></div>
			<div><h3><?php esc_html_e( 'Bathroom Vanities', 'zeus' ); ?></h3></div>
			<?php if ( 'euro-flat-panel' === $zeus_slug ) : ?>
				<div><h3><?php esc_html_e( 'Home Office / Built-Ins', 'zeus' ); ?></h3></div>
				<div><h3><?php esc_html_e( 'Other Custom Spaces', 'zeus' ); ?></h3></div>
			<?php else : ?>
				<div><h3><?php esc_html_e( 'Laundry / Pantry', 'zeus' ); ?></h3></div>
				<div><h3><?php esc_html_e( 'Custom Spaces', 'zeus' ); ?></h3></div>
			<?php endif; ?>
		</div>
		<p style="margin-top: var(--wp--preset--spacing--3);">
			<?php
			printf(
				/* translators: 1: link to Kitchen Cabinets, 2: link to Bathroom Cabinets & Vanities, 3: link to Home Office or Custom Spaces */
				wp_kses_post( __( 'See %1$s, %2$s, or %3$s.', 'zeus' ) ),
				'<a href="' . esc_url( $zeus_page_kitchen ? get_permalink( $zeus_page_kitchen ) : home_url( '/cabinets/kitchen-cabinets/' ) ) . '">' . esc_html__( 'Kitchen Cabinets', 'zeus' ) . '</a>',
				'<a href="' . esc_url( $zeus_page_bath ? get_permalink( $zeus_page_bath ) : home_url( '/cabinets/bathroom-cabinets-vanities/' ) ) . '">' . esc_html__( 'Bathroom Cabinets & Vanities', 'zeus' ) . '</a>',
				'euro-flat-panel' === $zeus_slug
					? '<a href="' . esc_url( $zeus_page_home_office ? get_permalink( $zeus_page_home_office ) : home_url( '/custom-spaces/home-office/' ) ) . '">' . esc_html__( 'Home Office', 'zeus' ) . '</a>'
					: '<a href="' . esc_url( home_url( '/custom-spaces/' ) ) . '">' . esc_html__( 'Custom Spaces', 'zeus' ) . '</a>'
			);
			?>
		</p>
	<?php zeus_section_end(); ?>

	<!-- 6. Gallery -->
	<?php if ( ! empty( $zeus_gallery_ids ) ) : ?>
		<?php
		// Skip the hero image here so it never repeats immediately below itself.
		$zeus_gallery_display = array_values( array_diff( $zeus_gallery_ids, array( $zeus_copy['hero_id'] ) ) );
		$zeus_gallery_display = array_slice( $zeus_gallery_display, 0, 6 );
		?>
		<?php if ( ! empty( $zeus_gallery_display ) ) : ?>
			<?php zeus_section_start( array( 'variant' => 'compact', 'eyebrow' => __( 'Gallery', 'zeus' ), 'heading' => sprintf( /* translators: %s: collection title */ __( '%s in Kitchens & Baths', 'zeus' ), get_the_title() ) ) ); ?>
				<div class="zeus-grid zeus-grid--3">
					<?php foreach ( $zeus_gallery_display as $zeus_att_id ) : ?>
						<?php echo wp_get_attachment_image( $zeus_att_id, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium);' ) ); ?>
					<?php endforeach; ?>
				</div>
			<?php zeus_section_end(); ?>
		<?php endif; ?>
	<?php endif; ?>

	<!-- 7. Process -->
	<?php zeus_section_start( array( 'variant' => 'stone', 'eyebrow' => __( 'Process', 'zeus' ), 'heading' => __( 'From Selection to Installation', 'zeus' ) ) ); ?>
		<div class="zeus-grid zeus-grid--4 zeus-process">
			<div class="zeus-process__step"><h3><?php esc_html_e( 'Choose a Style', 'zeus' ); ?></h3><p><?php esc_html_e( 'Confirm this collection is the right fit for your project.', 'zeus' ); ?></p></div>
			<div class="zeus-process__step"><h3><?php esc_html_e( 'Select Color / Finish', 'zeus' ); ?></h3><p><?php esc_html_e( 'Choose from the available colors and finishes.', 'zeus' ); ?></p></div>
			<div class="zeus-process__step"><h3><?php esc_html_e( 'Design & Measure', 'zeus' ); ?></h3><p><?php esc_html_e( 'We plan the layout and take precise measurements.', 'zeus' ); ?></p></div>
			<div class="zeus-process__step"><h3><?php esc_html_e( 'Delivery & Professional Installation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Delivery and installation coordinated by ZEUS.', 'zeus' ); ?></p></div>
		</div>
	<?php zeus_section_end(); ?>

	<!-- 8. Service area -->
	<?php zeus_section_start( array( 'eyebrow' => __( 'Service Area', 'zeus' ), 'heading' => __( 'Serving Orlando & Central Florida', 'zeus' ) ) ); ?>
		<p>
			<?php
			printf(
				/* translators: %s: collection title */
				esc_html__( 'ZEUS selects, designs, and installs %s cabinetry for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.', 'zeus' ),
				esc_html( get_the_title() )
			);
			?>
		</p>
		<p class="zeus-service-area__note">
			<?php esc_html_e( "Not sure if you're in our service area?", 'zeus' ); ?>
			<a href="<?php echo esc_url( zeus_phone_number_href() ); ?>"><?php esc_html_e( 'Call or text us.', 'zeus' ); ?></a>
		</p>
	<?php zeus_section_end(); ?>

	<!-- 9. FAQ -->
	<?php
	zeus_section_start(
		array(
			'variant' => 'compact',
			'eyebrow' => __( 'FAQ', 'zeus' ),
			'heading' => sprintf( /* translators: %s: collection title */ __( '%s Questions', 'zeus' ), get_the_title() ),
		)
	);

	$zeus_faq_sets = array(
		'brooklyn'        => array(
			array( 'q' => __( 'What colors are available in Brooklyn?', 'zeus' ), 'a' => __( 'Brooklyn is available in White, Pearl, Fawn, Gray, Slate, and Midnight.', 'zeus' ) ),
			array( 'q' => __( 'Is Brooklyn White available?', 'zeus' ), 'a' => __( 'Yes — White is one of six available Brooklyn colors.', 'zeus' ) ),
			array( 'q' => __( 'Can Brooklyn be used in a bathroom?', 'zeus' ), 'a' => __( 'Yes — Brooklyn works well for both kitchen cabinetry and bathroom vanities.', 'zeus' ) ),
			array( 'q' => __( 'Can ZEUS help coordinate countertops?', 'zeus' ), 'a' => __( 'Yes — cabinetry and countertop material are planned together during your consultation.', 'zeus' ) ),
			array( 'q' => __( 'Do you install the cabinets?', 'zeus' ), 'a' => __( 'Yes — delivery and professional installation are coordinated by ZEUS.', 'zeus' ) ),
		),
		'shaker'          => array(
			array( 'q' => __( 'What makes a cabinet Shaker style?', 'zeus' ), 'a' => __( 'A classic five-piece door — four frame pieces around a flat center panel, recessed slightly below the frame.', 'zeus' ) ),
			array( 'q' => __( 'What is the difference between Shaker and Slim Shaker?', 'zeus' ), 'a' => __( 'Both use the same recessed-panel construction. Traditional Shaker has a wider frame; our Oslo Slim Shaker collection uses a narrower rail for a cleaner look.', 'zeus' ) ),
			array( 'q' => __( 'Which Shaker colors are available?', 'zeus' ), 'a' => __( 'White, Sand, Kodiak, and Moss.', 'zeus' ) ),
			array( 'q' => __( 'Is Shaker White available?', 'zeus' ), 'a' => __( 'Yes — White is one of four available Shaker colors.', 'zeus' ) ),
			array( 'q' => __( 'Can Shaker be used in bathrooms?', 'zeus' ), 'a' => __( 'Yes — Shaker is a common choice for both kitchens and bathroom vanities.', 'zeus' ) ),
			array( 'q' => __( 'Can ZEUS coordinate countertops and installation?', 'zeus' ), 'a' => __( 'Yes — selection, design, delivery, and installation are all coordinated through ZEUS.', 'zeus' ) ),
		),
		'oslo'            => array(
			array( 'q' => __( 'What is Slim Shaker cabinetry?', 'zeus' ), 'a' => __( 'A modern take on the Shaker door — the same recessed-panel construction with a narrower rail for a cleaner, more architectural look.', 'zeus' ) ),
			array( 'q' => __( 'How is Slim Shaker different from traditional Shaker?', 'zeus' ), 'a' => __( 'The door geometry is the same; Slim Shaker uses a narrower frame width around the center panel.', 'zeus' ) ),
			array( 'q' => __( 'Is Oslo available in white?', 'zeus' ), 'a' => __( 'Yes — Oslo is available in White, Oak, and Walnut.', 'zeus' ) ),
			array( 'q' => __( 'What is OSLO Classic Walnut?', 'zeus' ), 'a' => __( 'OSLO Classic Walnut is the Walnut finish in the Oslo Slim Shaker collection — a natural walnut finish paired with the narrower Slim Shaker profile.', 'zeus' ) ),
			array( 'q' => __( 'Can Oslo be used for a modern kitchen?', 'zeus' ), 'a' => __( 'Yes — Oslo\'s cleaner, narrower frame suits modern and streamlined kitchen designs.', 'zeus' ) ),
			array( 'q' => __( 'Can ZEUS coordinate countertops and installation?', 'zeus' ), 'a' => __( 'Yes — selection, design, delivery, and installation are all coordinated through ZEUS.', 'zeus' ) ),
		),
		'euro-flat-panel' => array(
			array( 'q' => __( 'What are Euro flat-panel cabinets?', 'zeus' ), 'a' => __( 'A European-style cabinet line with a single flat door face and no raised or recessed panel detail, for a streamlined, minimal-hardware look.', 'zeus' ) ),
			array( 'q' => __( 'Are flat-panel cabinets the same as Shaker?', 'zeus' ), 'a' => __( 'No — Shaker (and Slim Shaker) are framed doors with a recessed center panel. Flat panel has no frame at all.', 'zeus' ) ),
			array( 'q' => __( 'What is the difference between Flat Panel and Slim Shaker?', 'zeus' ), 'a' => __( 'Flat Panel is a completely slab-front visual with no lines. Slim Shaker has a narrow but visible framed profile.', 'zeus' ) ),
			array( 'q' => __( 'Can flat-panel cabinets be used in bathrooms?', 'zeus' ), 'a' => __( 'Yes — flat-panel cabinetry works well for bathroom vanities as well as kitchens and built-ins.', 'zeus' ) ),
			array( 'q' => __( 'Can ZEUS create custom flat-panel cabinetry?', 'zeus' ), 'a' => __( 'Yes — beyond in-stock options, ZEUS can build custom flat-panel cabinetry for built-ins and non-standard spaces.', 'zeus' ) ),
			array( 'q' => __( 'Can ZEUS coordinate countertops and installation?', 'zeus' ), 'a' => __( 'Yes — selection, design, delivery, and installation are all coordinated through ZEUS.', 'zeus' ) ),
		),
	);
	$zeus_faqs = $zeus_faq_sets[ $zeus_slug ] ?? array();
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

	<!-- 10. Final CTA / consultation form -->
	<?php
	zeus_section_start(
		array(
			'variant'   => 'compact',
			'eyebrow'   => __( 'Get Started', 'zeus' ),
			'heading'   => sprintf( /* translators: %s: collection title */ __( 'Considering %s for Your Project?', 'zeus' ), get_the_title() ),
			'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
			'container' => 'narrow',
		)
	);
	get_template_part( 'template-parts/consultation-form' );
	zeus_section_end();

endwhile;

get_footer();
