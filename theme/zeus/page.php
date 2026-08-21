<?php
/**
 * Generic page template.
 */
get_header();
zeus_render_breadcrumbs();
?>
<article class="zeus-container zeus-container--narrow zeus-section--tight zeus-section">
	<?php while ( have_posts() ) : the_post(); ?>
		<h1><?php the_title(); ?></h1>
		<div class="zeus-entry-content">
			<?php the_content(); ?>
		</div>
	<?php endwhile; ?>
</article>
<?php get_footer(); ?>
