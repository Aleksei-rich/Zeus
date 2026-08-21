<?php
/**
 * 404.
 */
get_header();
?>
<div class="zeus-container zeus-container--narrow zeus-section" style="text-align:center;">
	<h1><?php esc_html_e( 'Page Not Found', 'zeus' ); ?></h1>
	<p><?php esc_html_e( "The page you're looking for doesn't exist or has moved.", 'zeus' ); ?></p>
	<p>
		<a class="zeus-btn zeus-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'zeus' ); ?></a>
	</p>
</div>
<?php get_footer(); ?>
