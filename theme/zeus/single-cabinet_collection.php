<?php
/**
 * Single cabinet collection (e.g. /cabinet-styles/oslo/).
 */
get_header();
zeus_render_breadcrumbs();

while ( have_posts() ) :
	the_post();
	$zeus_id           = get_the_ID();
	$zeus_profile_type = get_post_meta( $zeus_id, 'zeus_profile_type', true );
	$zeus_notes        = get_post_meta( $zeus_id, 'zeus_construction_notes', true );
	$zeus_finishes     = get_the_terms( $zeus_id, 'finish' );
	$zeus_gallery_ids  = zeus_get_gallery_ids( $zeus_id );
	$zeus_swatches     = get_post_meta( $zeus_id, 'zeus_finish_swatches', true );
	$zeus_swatches     = is_array( $zeus_swatches ) ? $zeus_swatches : array();
	?>
	<article class="zeus-container zeus-container--wide zeus-section--tight zeus-section">
		<div class="zeus-section__header">
			<?php if ( $zeus_profile_type ) : ?>
				<p class="zeus-section__eyebrow"><?php echo esc_html( $zeus_profile_type ); ?></p>
			<?php endif; ?>
			<h1><?php the_title(); ?></h1>
			<?php if ( get_the_excerpt() ) : ?>
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<div style="margin-bottom: var(--wp--preset--spacing--4);"><?php the_post_thumbnail( 'zeus-hero' ); ?></div>
		<?php endif; ?>

		<div class="zeus-entry-content">
			<?php the_content(); ?>
		</div>

		<?php if ( $zeus_finishes && ! is_wp_error( $zeus_finishes ) ) : ?>
			<div style="margin-top: var(--wp--preset--spacing--5);">
				<h2><?php esc_html_e( 'Available Finishes', 'zeus' ); ?></h2>
				<div class="zeus-swatch-grid">
					<?php foreach ( $zeus_finishes as $zeus_finish ) : ?>
						<div class="zeus-swatch">
							<?php if ( ! empty( $zeus_swatches[ $zeus_finish->term_id ] ) ) : ?>
								<?php echo wp_get_attachment_image( $zeus_swatches[ $zeus_finish->term_id ], 'zeus-square', false, array( 'class' => 'zeus-swatch__img' ) ); ?>
							<?php endif; ?>
							<span class="zeus-swatch__label"><?php echo esc_html( $zeus_finish->name ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ( 'oslo' === get_post_field( 'post_name', $zeus_id ) ) : ?>
					<p style="margin-top: var(--wp--preset--spacing--2); color: var(--wp--preset--color--stone-600);">
						<?php esc_html_e( 'Oslo is a Slim Shaker profile — a narrower-rail take on the Shaker door, distinct from the traditional Shaker collection. The Walnut finish is the OSLO Classic Walnut Slim Shaker Kitchen Cabinets.', 'zeus' ); ?>
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $zeus_notes ) : ?>
			<div style="margin-top: var(--wp--preset--spacing--4);">
				<h2><?php esc_html_e( 'Material & Construction', 'zeus' ); ?></h2>
				<p><?php echo wp_kses_post( $zeus_notes ); ?></p>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $zeus_gallery_ids ) ) : ?>
			<div style="margin-top: var(--wp--preset--spacing--4);">
				<h2><?php esc_html_e( 'Gallery', 'zeus' ); ?></h2>
				<div class="zeus-grid zeus-grid--3">
					<?php foreach ( $zeus_gallery_ids as $zeus_att_id ) : ?>
						<?php echo wp_get_attachment_image( $zeus_att_id, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium);' ) ); ?>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</article>
	<?php
endwhile;

get_template_part( 'components/cta-section' );
get_footer();
