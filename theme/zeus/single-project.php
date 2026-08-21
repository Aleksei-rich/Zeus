<?php
/**
 * Single portfolio project. Always renders the completed/concept badge —
 * never presents a 3D design concept as finished work.
 */
get_header();
zeus_render_breadcrumbs();

while ( have_posts() ) :
	the_post();
	$zeus_id        = get_the_ID();
	$zeus_completed = zeus_project_is_completed( $zeus_id );
	$zeus_location  = get_post_meta( $zeus_id, 'zeus_location', true );
	$zeus_decisions = get_post_meta( $zeus_id, 'zeus_design_decisions', true );
	$zeus_before_id = (int) get_post_meta( $zeus_id, 'zeus_before_image', true );
	$zeus_after_id  = (int) get_post_meta( $zeus_id, 'zeus_after_image', true );
	$zeus_gallery   = zeus_get_gallery_ids( $zeus_id );
	$zeus_cta_text  = get_post_meta( $zeus_id, 'zeus_cta_variant', true );

	$zeus_types       = get_the_terms( $zeus_id, 'project_type' );
	$zeus_areas       = get_the_terms( $zeus_id, 'service_area' );
	$zeus_styles      = get_the_terms( $zeus_id, 'cabinetry_style' );
	$zeus_countertops = get_the_terms( $zeus_id, 'countertop_material' );
	?>
	<article class="zeus-container zeus-section--tight zeus-section">
		<div class="zeus-section__header">
			<span class="zeus-card__badge <?php echo $zeus_completed ? 'zeus-card__badge--completed' : 'zeus-card__badge--concept'; ?>">
				<?php echo esc_html( zeus_project_status_label( $zeus_id ) ); ?>
			</span>
			<h1><?php the_title(); ?></h1>
			<?php if ( $zeus_location ) : ?>
				<p><?php echo esc_html( $zeus_location ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<div style="margin-bottom: var(--wp--preset--spacing--4);"><?php the_post_thumbnail( 'zeus-hero' ); ?></div>
		<?php endif; ?>

		<div class="zeus-grid zeus-grid--3" style="margin-bottom: var(--wp--preset--spacing--4);">
			<?php if ( $zeus_types && ! is_wp_error( $zeus_types ) ) : ?>
				<div><strong><?php esc_html_e( 'Project Type', 'zeus' ); ?></strong><p><?php echo esc_html( implode( ', ', wp_list_pluck( $zeus_types, 'name' ) ) ); ?></p></div>
			<?php endif; ?>
			<?php if ( $zeus_styles && ! is_wp_error( $zeus_styles ) ) : ?>
				<div><strong><?php esc_html_e( 'Cabinetry Style', 'zeus' ); ?></strong><p><?php echo esc_html( implode( ', ', wp_list_pluck( $zeus_styles, 'name' ) ) ); ?></p></div>
			<?php endif; ?>
			<?php if ( $zeus_countertops && ! is_wp_error( $zeus_countertops ) ) : ?>
				<div><strong><?php esc_html_e( 'Countertop', 'zeus' ); ?></strong><p><?php echo esc_html( implode( ', ', wp_list_pluck( $zeus_countertops, 'name' ) ) ); ?></p></div>
			<?php endif; ?>
		</div>

		<div class="zeus-entry-content">
			<?php the_content(); ?>
		</div>

		<?php if ( $zeus_decisions ) : ?>
			<div style="margin-top: var(--wp--preset--spacing--4);">
				<h2><?php esc_html_e( 'Design Decisions', 'zeus' ); ?></h2>
				<p><?php echo wp_kses_post( $zeus_decisions ); ?></p>
			</div>
		<?php endif; ?>

		<?php if ( $zeus_before_id && $zeus_after_id ) : ?>
			<div style="margin-top: var(--wp--preset--spacing--4);">
				<h2><?php esc_html_e( 'Before & After', 'zeus' ); ?></h2>
				<div class="zeus-grid zeus-grid--2">
					<figure><?php echo wp_get_attachment_image( $zeus_before_id, 'zeus-gallery' ); ?><figcaption><?php esc_html_e( 'Before', 'zeus' ); ?></figcaption></figure>
					<figure><?php echo wp_get_attachment_image( $zeus_after_id, 'zeus-gallery' ); ?><figcaption><?php esc_html_e( 'After', 'zeus' ); ?></figcaption></figure>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $zeus_gallery ) ) : ?>
			<div style="margin-top: var(--wp--preset--spacing--4);">
				<h2><?php esc_html_e( 'Gallery', 'zeus' ); ?></h2>
				<div class="zeus-grid zeus-grid--3">
					<?php foreach ( $zeus_gallery as $zeus_att_id ) : ?>
						<?php echo wp_get_attachment_image( $zeus_att_id, 'zeus-gallery', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium);' ) ); ?>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</article>
	<?php
	get_template_part(
		'components/cta-section',
		null,
		$zeus_cta_text ? array( 'heading' => $zeus_cta_text ) : array()
	);
endwhile;

get_footer();
