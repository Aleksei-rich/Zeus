<?php
/**
 * Portfolio hub (/portfolio/). Only verified ZEUS projects are rendered.
 */
get_header();
zeus_render_breadcrumbs();
?>
<section class="zeus-section zeus-section--tight zeus-portfolio-intro">
	<div class="zeus-container">
		<div class="zeus-section__header zeus-section__header--centered">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Real ZEUS Work', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Portfolio', 'zeus' ); ?></h1>
			<p><?php esc_html_e( 'Completed ZEUS cabinetry, countertop and built-in projects from Orlando and Central Florida.', 'zeus' ); ?></p>
		</div>
		<div class="zeus-prose zeus-prose--wide zeus-portfolio-intro__copy">
			<p><?php esc_html_e( 'This portfolio is built from verified completed ZEUS work rather than generic stock imagery. As additional real-project photography is reviewed and grouped with confidence, more kitchens, bathroom vanities, closets, laundry and pantry cabinetry, home offices and countertop installations will be added here.', 'zeus' ); ?></p>
		</div>
	</div>
</section>

<section class="zeus-section zeus-section--stone zeus-portfolio-grid-section">
	<div class="zeus-container">
	<?php if ( have_posts() ) : ?>
		<div class="zeus-grid zeus-grid--3 zeus-portfolio-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'components/card-project', null, array( 'post' => get_post() ) );
			endwhile;
			?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<div class="zeus-prose zeus-prose--wide">
			<p><?php esc_html_e( "We're adding verified completed-project photography to this page. Request a free consultation to discuss your project and see relevant examples.", 'zeus' ); ?></p>
		</div>
	<?php endif; ?>
	</div>
</section>
<?php
get_template_part( 'components/cta-section' );
get_footer();
