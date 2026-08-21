<?php
/**
 * Fallback template (generic post loop).
 */
get_header();
zeus_render_breadcrumbs();
?>
<div class="zeus-container zeus-section--tight zeus-section">
	<?php if ( have_posts() ) : ?>
		<div class="zeus-grid zeus-grid--3">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'zeus-card' ); ?>>
					<div class="zeus-card__body">
						<h2 class="zeus-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="zeus-card__desc"><?php echo esc_html( get_the_excerpt() ); ?></p>
					</div>
				</article>
				<?php
			endwhile;
			?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing found.', 'zeus' ); ?></p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
