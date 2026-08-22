<?php
/**
 * Blog listing (Posts page — /blog/).
 */
get_header();
zeus_render_breadcrumbs();
?>
<div class="zeus-container zeus-section--tight zeus-section">
	<div class="zeus-section__header">
		<h1><?php esc_html_e( 'Blog', 'zeus' ); ?></h1>
	</div>

	<?php if ( have_posts() ) : ?>
		<div class="zeus-grid zeus-grid--3">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'zeus-card' ); ?>>
					<div class="zeus-card__media">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'zeus-card' ); ?>
						<?php endif; ?>
					</div>
					<div class="zeus-card__body">
						<h2 class="zeus-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="zeus-card__meta"><?php echo esc_html( get_the_date() ); ?></p>
						<p class="zeus-card__desc"><?php echo esc_html( get_the_excerpt() ); ?></p>
					</div>
				</article>
				<?php
			endwhile;
			?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( "We haven't published any articles yet — check back soon.", 'zeus' ); ?></p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
