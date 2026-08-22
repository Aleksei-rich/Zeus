<?php
/**
 * Cabinet collection card. Usage:
 * get_template_part( 'components/card-collection', null, array( 'post' => $post ) );
 */
$zeus_post = $args['post'] ?? null;
if ( ! $zeus_post instanceof WP_Post ) {
	return;
}
$zeus_id  = $zeus_post->ID;
$zeus_url = get_permalink( $zeus_id );
?>
<article class="zeus-card">
	<?php if ( has_post_thumbnail( $zeus_id ) ) : ?>
		<div class="zeus-card__media">
			<?php echo get_the_post_thumbnail( $zeus_id, 'zeus-card', array( 'alt' => esc_attr( get_the_title( $zeus_id ) . ' cabinet collection' ) ) ); ?>
		</div>
	<?php endif; ?>
	<div class="zeus-card__body">
		<h3 class="zeus-card__title"><a href="<?php echo esc_url( $zeus_url ); ?>"><?php echo esc_html( get_the_title( $zeus_id ) ); ?></a></h3>
		<p class="zeus-card__desc"><?php echo esc_html( get_the_excerpt( $zeus_id ) ); ?></p>
	</div>
</article>
