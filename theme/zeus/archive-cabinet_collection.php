<?php
/**
 * Cabinet Styles hub (/cabinet-styles/).
 */
get_header();
zeus_render_breadcrumbs();
?>
<div class="zeus-container zeus-section--tight zeus-section">
	<div class="zeus-section__header">
		<h1><?php esc_html_e( 'Cabinet Styles', 'zeus' ); ?></h1>
		<p><?php esc_html_e( 'Explore our cabinet collections — from transitional Brooklyn to Slim Shaker Oslo.', 'zeus' ); ?></p>
	</div>

	<?php if ( have_posts() ) : ?>
		<div class="zeus-grid zeus-grid--4">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'components/card-collection', null, array( 'post' => get_post() ) );
			endwhile;
			?>
		</div>
	<?php endif; ?>
</div>
<?php
get_template_part( 'components/cta-section' );
get_footer();
