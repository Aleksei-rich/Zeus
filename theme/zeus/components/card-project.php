<?php
/**
 * Portfolio project card. Usage:
 * get_template_part( 'components/card-project', null, array( 'post' => $post ) );
 * Always shows an honest completed/concept badge — never omits it.
 */
$zeus_post = $args['post'] ?? null;
if ( ! $zeus_post instanceof WP_Post ) {
	return;
}
$zeus_id        = $zeus_post->ID;
$zeus_url       = get_permalink( $zeus_id );
$zeus_completed = zeus_project_is_completed( $zeus_id );
$zeus_location  = get_post_meta( $zeus_id, 'zeus_location', true );
?>
<article class="zeus-card">
	<div class="zeus-card__media">
		<?php if ( has_post_thumbnail( $zeus_id ) ) : ?>
			<?php echo get_the_post_thumbnail( $zeus_id, 'zeus-card', array( 'alt' => esc_attr( get_the_title( $zeus_id ) ) ) ); ?>
		<?php endif; ?>
	</div>
	<div class="zeus-card__body">
		<span class="zeus-card__badge <?php echo $zeus_completed ? 'zeus-card__badge--completed' : 'zeus-card__badge--concept'; ?>">
			<?php echo esc_html( zeus_project_status_label( $zeus_id ) ); ?>
		</span>
		<h3 class="zeus-card__title"><a href="<?php echo esc_url( $zeus_url ); ?>"><?php echo esc_html( get_the_title( $zeus_id ) ); ?></a></h3>
		<?php if ( $zeus_location ) : ?>
			<p class="zeus-card__meta"><?php echo esc_html( $zeus_location ); ?></p>
		<?php endif; ?>
	</div>
</article>
