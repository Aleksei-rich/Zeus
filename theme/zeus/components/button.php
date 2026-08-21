<?php
/**
 * Button component. Usage:
 * get_template_part( 'components/button', null, array(
 *     'label' => 'Request Free Consultation', 'url' => '#', 'variant' => 'primary',
 * ) );
 */
$zeus_label   = $args['label'] ?? '';
$zeus_url     = $args['url'] ?? '#';
$zeus_variant = $args['variant'] ?? 'primary';
$zeus_on_dark = ! empty( $args['on_dark'] );
$zeus_classes = 'zeus-btn zeus-btn--' . esc_attr( $zeus_variant ) . ( $zeus_on_dark ? ' zeus-btn--on-dark' : '' );
?>
<a class="<?php echo esc_attr( $zeus_classes ); ?>" href="<?php echo esc_url( $zeus_url ); ?>">
	<?php echo esc_html( $zeus_label ); ?>
</a>
