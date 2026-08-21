<?php
/**
 * Service card. Usage:
 * get_template_part( 'components/card-service', null, array(
 *     'title' => '...', 'desc' => '...', 'url' => '...',
 * ) );
 */
$zeus_title = $args['title'] ?? '';
$zeus_desc  = $args['desc'] ?? '';
$zeus_url   = $args['url'] ?? '#';
?>
<article class="zeus-card">
	<div class="zeus-card__body">
		<h3 class="zeus-card__title"><a href="<?php echo esc_url( $zeus_url ); ?>"><?php echo esc_html( $zeus_title ); ?></a></h3>
		<p class="zeus-card__desc"><?php echo esc_html( $zeus_desc ); ?></p>
	</div>
</article>
