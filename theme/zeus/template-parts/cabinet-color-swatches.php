<?php
/**
 * Clickable finish/color swatch grid. Shared by single-cabinet_collection.php
 * (a collection's own "Colors" section) and single-cabinet-color.php (the
 * "Other Colors" section on a color detail page). Usage:
 *
 * get_template_part( 'template-parts/cabinet-color-swatches', null, array(
 *     'style_slug' => 'brooklyn',
 *     'finishes'   => $wp_term_objects,        // ordered array of 'finish' WP_Term
 *     'swatches'   => $term_id_to_attachment_id_map,
 *     'current'    => 'white',                 // color slug being viewed, '' on the collection page
 * ) );
 *
 * A swatch only becomes a link when a published color page exists for
 * that style+color (zeus_get_cabinet_color_content()) -- otherwise it
 * renders the same static, non-interactive markup as before, so
 * collections without curated color pages yet (Shaker, Oslo, Euro) are
 * visually and functionally unchanged.
 */

$zeus_style_slug = $args['style_slug'] ?? '';
$zeus_finishes   = $args['finishes'] ?? array();
$zeus_swatches   = $args['swatches'] ?? array();
$zeus_current    = $args['current'] ?? '';

if ( empty( $zeus_finishes ) ) {
	return;
}
?>
<div class="zeus-swatch-grid">
	<?php foreach ( $zeus_finishes as $zeus_finish ) : ?>
		<?php
		$zeus_is_current = ( $zeus_finish->slug === $zeus_current );
		$zeus_has_page   = ! $zeus_is_current && zeus_get_cabinet_color_content( $zeus_style_slug, $zeus_finish->slug );
		$zeus_label      = ( 'oslo' === $zeus_style_slug && 'Walnut' === $zeus_finish->name )
			? __( 'OSLO Classic Walnut', 'zeus' )
			: $zeus_finish->name;

		$zeus_classes = array( 'zeus-swatch' );
		if ( 'White' === $zeus_finish->name ) {
			$zeus_classes[] = 'zeus-swatch--featured';
		}
		if ( $zeus_is_current ) {
			$zeus_classes[] = 'zeus-swatch--current';
		}
		$zeus_class_attr = esc_attr( implode( ' ', $zeus_classes ) );
		?>
		<?php if ( $zeus_has_page ) : ?>
			<a class="<?php echo $zeus_class_attr; ?>" href="<?php echo esc_url( zeus_cabinet_color_url( $zeus_style_slug, $zeus_finish->slug ) ); ?>">
		<?php else : ?>
			<div class="<?php echo $zeus_class_attr; ?>"<?php echo $zeus_is_current ? ' aria-current="page"' : ''; ?>>
		<?php endif; ?>
				<?php if ( ! empty( $zeus_swatches[ $zeus_finish->term_id ] ) ) : ?>
					<?php echo wp_get_attachment_image( $zeus_swatches[ $zeus_finish->term_id ], 'zeus-square', false, array( 'class' => 'zeus-swatch__img' ) ); ?>
				<?php endif; ?>
				<span class="zeus-swatch__label">
					<?php echo esc_html( $zeus_label ); ?>
					<?php if ( $zeus_is_current ) : ?>
						<span class="zeus-swatch__current-tag"><?php esc_html_e( '(viewing)', 'zeus' ); ?></span>
					<?php endif; ?>
				</span>
		<?php if ( $zeus_has_page ) : ?>
			</a>
		<?php else : ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
