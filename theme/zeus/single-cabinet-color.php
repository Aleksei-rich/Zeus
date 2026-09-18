<?php
/**
 * Cabinet color detail page -- e.g. /cabinet-styles/brooklyn/white/.
 *
 * Virtual template: not part of WordPress's normal template-hierarchy
 * naming on purpose. The underlying request is a normal cabinet_collection
 * singular query (see docs/CONTENT-MODEL.md, "Cabinet Color Pages"), and
 * theme/zeus/inc/cabinet-colors.php's `template_include` filter swaps in
 * this file instead of single-cabinet_collection.php whenever a
 * validated color slug is present. Reusable across every future
 * style/color combination -- adding one is a data change in
 * plugins/zeus-core/inc/cabinet-colors.php, not a new template.
 */
get_header();
zeus_render_breadcrumbs();

while ( have_posts() ) :
	the_post();

	$zeus_collection_id = get_the_ID();
	$zeus_style_slug    = get_post_field( 'post_name', $zeus_collection_id );
	$zeus_color_slug    = get_query_var( 'zeus_cabinet_color' );
	$zeus_color         = zeus_get_cabinet_color_content( $zeus_style_slug, $zeus_color_slug );

	// Guarded already by the plugin's pre_get_posts 404 gate -- this is a
	// defensive stop, never expected to actually trigger.
	if ( ! $zeus_color ) {
		get_footer();
		return;
	}

	$zeus_collection_title = get_the_title( $zeus_collection_id );
	$zeus_collection_url   = get_permalink( $zeus_collection_id );
	$zeus_finish_term      = get_term_by( 'slug', $zeus_color_slug, 'finish' );
	$zeus_color_name       = $zeus_finish_term ? $zeus_finish_term->name : ucfirst( $zeus_color_slug );

	$zeus_swatches = get_post_meta( $zeus_collection_id, 'zeus_finish_swatches', true );
	$zeus_swatches = is_array( $zeus_swatches ) ? $zeus_swatches : array();

	// Same display order as single-cabinet_collection.php's own finish
	// grid, keyed by style so this scales to future styles without code
	// changes beyond adding their own order here.
	$zeus_finish_order  = array(
		'brooklyn' => array( 'White', 'Pearl', 'Fawn', 'Gray', 'Slate', 'Midnight' ),
	);
	$zeus_ordered_names = $zeus_finish_order[ $zeus_style_slug ] ?? array();

	$zeus_ordered_finishes = array();
	foreach ( $zeus_ordered_names as $zeus_finish_name ) {
		$zeus_term = get_term_by( 'name', $zeus_finish_name, 'finish' );
		if ( $zeus_term ) {
			$zeus_ordered_finishes[] = $zeus_term;
		}
	}
	?>

	<!-- 1. Hero -->
	<section class="zeus-hero" aria-label="<?php echo esc_attr( $zeus_color['h1'] ); ?>">
		<div class="zeus-hero__media">
			<?php
			echo wp_get_attachment_image(
				$zeus_color['hero_id'],
				'zeus-hero',
				false,
				array(
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'class'         => 'zeus-hero__img',
					'alt'           => $zeus_color['hero_alt'],
				)
			);
			?>
			<div class="zeus-hero__scrim" aria-hidden="true"></div>
		</div>
		<div class="zeus-container">
			<div class="zeus-hero__content">
				<p class="zeus-section__eyebrow">
					<?php
					printf(
						/* translators: %s: collection title, e.g. "Brooklyn" */
						esc_html__( '%s Collection', 'zeus' ),
						esc_html( $zeus_collection_title )
					);
					?>
				</p>
				<h1><?php echo esc_html( $zeus_color['h1'] ); ?></h1>
				<div class="zeus-cta__actions" style="justify-content:flex-start;">
					<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
					<?php
					get_template_part(
						'components/button',
						null,
						array(
							/* translators: %s: collection title, e.g. "Brooklyn" */
							'label'   => sprintf( __( 'All %s Colors', 'zeus' ), $zeus_collection_title ),
							'url'     => $zeus_collection_url,
							'variant' => 'secondary',
							'on_dark' => true,
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<!-- 2. Introduction -->
	<?php zeus_section_start( array( 'eyebrow' => __( 'Design', 'zeus' ), 'heading' => __( 'Design Character', 'zeus' ) ) ); ?>
		<?php foreach ( $zeus_color['intro'] as $zeus_paragraph ) : ?>
			<p><?php echo esc_html( $zeus_paragraph ); ?></p>
		<?php endforeach; ?>
	<?php zeus_section_end(); ?>

	<!-- 3. Gallery -->
	<?php if ( ! empty( $zeus_color['gallery'] ) ) : ?>
		<?php
		zeus_section_start(
			array(
				'variant' => 'stone',
				'eyebrow' => __( 'Gallery', 'zeus' ),
				'heading' => sprintf(
					/* translators: 1: collection title, 2: color name */
					__( '%1$s %2$s in Kitchens & Baths', 'zeus' ),
					$zeus_collection_title,
					$zeus_color_name
				),
			)
		);
		?>
			<div class="zeus-grid zeus-grid--3">
				<?php foreach ( $zeus_color['gallery'] as $zeus_image ) : ?>
					<?php
					echo wp_get_attachment_image(
						$zeus_image['id'],
						'zeus-card',
						false,
						array(
							'loading' => 'lazy',
							'alt'     => $zeus_image['alt'],
							'style'   => 'border-radius:var(--wp--custom--radius--medium);',
						)
					);
					?>
				<?php endforeach; ?>
			</div>
		<?php zeus_section_end(); ?>
	<?php endif; ?>

	<!-- 4. Style context -->
	<?php zeus_section_start( array( 'eyebrow' => __( 'Collection', 'zeus' ), 'heading' => sprintf( /* translators: %s: collection title */ __( 'Part of the %s Collection', 'zeus' ), $zeus_collection_title ) ) ); ?>
		<p>
			<?php
			printf(
				/* translators: 1: color name, 2: link to the collection page, 3: collection title */
				wp_kses_post( __( '%1$s is one of the colors available in the %2$s -- see the full range and how the door profile itself works.', 'zeus' ) ),
				esc_html( $zeus_color_name ),
				'<a href="' . esc_url( $zeus_collection_url ) . '">' . esc_html( sprintf( /* translators: %s: collection title */ __( '%s collection', 'zeus' ), $zeus_collection_title ) ) . '</a>',
				esc_html( $zeus_collection_title )
			);
			?>
		</p>
	<?php zeus_section_end(); ?>

	<!-- 5. Other colors -->
	<?php if ( count( $zeus_ordered_finishes ) > 1 ) : ?>
		<?php zeus_section_start( array( 'variant' => 'stone', 'eyebrow' => __( 'Colors', 'zeus' ), 'heading' => sprintf( /* translators: %s: collection title */ __( 'Other %s Colors', 'zeus' ), $zeus_collection_title ) ) ); ?>
			<?php
			get_template_part(
				'template-parts/cabinet-color-swatches',
				null,
				array(
					'style_slug' => $zeus_style_slug,
					'finishes'   => $zeus_ordered_finishes,
					'swatches'   => $zeus_swatches,
					'current'    => $zeus_color_slug,
				)
			);
			?>
		<?php zeus_section_end(); ?>
	<?php endif; ?>

	<!-- 6. Final CTA / consultation form -->
	<?php
	zeus_section_start(
		array(
			'variant'   => 'compact',
			'eyebrow'   => __( 'Get Started', 'zeus' ),
			'heading'   => sprintf(
				/* translators: 1: collection title, 2: color name */
				__( 'Considering %1$s %2$s for Your Project?', 'zeus' ),
				$zeus_collection_title,
				$zeus_color_name
			),
			'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
			'container' => 'narrow',
		)
	);
	get_template_part( 'template-parts/consultation-form' );
	zeus_section_end();

endwhile;

get_footer();
