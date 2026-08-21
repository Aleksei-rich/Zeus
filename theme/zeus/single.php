<?php
/**
 * Single blog post.
 */
get_header();
zeus_render_breadcrumbs();
?>
<article class="zeus-container zeus-container--narrow zeus-section--tight zeus-section">
	<?php while ( have_posts() ) : the_post(); ?>
		<h1><?php the_title(); ?></h1>
		<p class="zeus-card__meta"><?php echo esc_html( get_the_date() ); ?></p>
		<?php if ( has_post_thumbnail() ) : ?>
			<div style="margin-block: var(--wp--preset--spacing--3);"><?php the_post_thumbnail( 'zeus-hero' ); ?></div>
		<?php endif; ?>
		<div class="zeus-entry-content">
			<?php the_content(); ?>
		</div>
	<?php endwhile; ?>
</article>
<?php get_template_part( 'components/cta-section' ); ?>
<?php get_footer(); ?>
