<?php
/**
 * Template Name: Consultation Form
 */
get_header();
zeus_render_breadcrumbs();
?>
<div class="zeus-container zeus-container--narrow zeus-section--tight zeus-section">
	<div class="zeus-section__header">
		<h1><?php esc_html_e( 'Request Free Consultation', 'zeus' ); ?></h1>
		<p><?php esc_html_e( 'Tell us about your project and we\'ll be in touch to schedule your free, no-obligation consultation.', 'zeus' ); ?></p>
	</div>
	<?php get_template_part( 'template-parts/consultation-form' ); ?>
</div>
<?php get_footer(); ?>
