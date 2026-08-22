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
			<?php if ( has_post_thumbnail() ) : ?>
			<div class="zeus-page-thumb"><?php the_post_thumbnail( 'zeus-hero', array( 'class' => 'zeus-page-thumb__img' ) ); ?></div>
			<?php endif; ?>
		<div class="zeus-entry-content">
			<?php the_content(); ?>
		</div>
	<?php endwhile; ?>
</article>
<?php get_footer(); ?>
