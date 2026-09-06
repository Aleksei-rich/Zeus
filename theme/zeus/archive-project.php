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
		<p><?php esc_html_e( 'Real ZEUS cabinet, countertop and built-in projects across Orlando and Central Florida.', 'zeus' ); ?></p>
	</div>

	<div class="zeus-prose">
		<p><?php esc_html_e( 'This portfolio is being built from completed ZEUS projects so visitors can evaluate real layouts, finishes and installation details rather than generic stock imagery. Our work includes kitchens, bathroom vanities, closets, laundry and pantry cabinetry, home offices and stone countertops.', 'zeus' ); ?></p>
		<p><?php esc_html_e( 'ZEUS coordinates cabinet selection, measurements, design, delivery, assembly and installation, with countertop fabrication and installation available as part of the same project. Cabinet options include Shaker, Slim Shaker, Brooklyn and Euro flat-panel styles, while countertop materials include quartz, granite, porcelain and marble.', 'zeus' ); ?></p>
		<p><?php esc_html_e( 'We serve homeowners, investors, flippers and renovation companies throughout Orlando, Windermere, Winter Garden, Horizon West, Clermont and surrounding Central Florida communities. As more completed-project photography is organized, this page will expand with individual project details and images.', 'zeus' ); ?></p>
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
