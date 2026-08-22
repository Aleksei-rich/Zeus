<?php
/**
 * Portfolio hub (/portfolio/). No fake projects are ever seeded — if
 * empty, an honest "in progress" message is shown instead of filler.
 */
get_header();
zeus_render_breadcrumbs();
?>
<div class="zeus-container zeus-section--tight zeus-section">
	<div class="zeus-section__header">
		<h1><?php esc_html_e( 'Portfolio', 'zeus' ); ?></h1>
		<p><?php esc_html_e( 'Real ZEUS projects across Orlando and Central Florida.', 'zeus' ); ?></p>
	</div>

	<?php if ( have_posts() ) : ?>
		<div class="zeus-grid zeus-grid--3">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'components/card-project', null, array( 'post' => get_post() ) );
			endwhile;
			?>
		</div>
	<?php else : ?>
		<p><?php esc_html_e( "We're adding real project photography to this page. In the meantime, request a free consultation to talk through your project and see examples.", 'zeus' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_template_part( 'components/cta-section' );
get_footer();
