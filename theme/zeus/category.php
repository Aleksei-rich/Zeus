<?php
/**
 * Category archive template for editorial guide hubs.
 */

get_header();
zeus_render_breadcrumbs();

$term        = get_queried_object();
$title       = single_cat_title( '', false );
$description = term_description( $term );
?>
<section class="zeus-section zeus-section--tight">
	<div class="zeus-container">
		<header class="zeus-section__header">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'ZEUS Guides', 'zeus' ); ?></p>
			<h1><?php echo esc_html( $title ); ?></h1>
			<?php if ( $description ) : ?>
				<div class="zeus-prose"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
			<?php endif; ?>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="zeus-grid zeus-grid--3">
				<?php while ( have_posts() ) : the_post(); ?>
					<article <?php post_class( 'zeus-card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="zeus-card__media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
								<?php the_post_thumbnail( 'zeus-card', array( 'loading' => 'lazy' ) ); ?>
							</a>
						<?php endif; ?>
						<div class="zeus-card__body">
							<h2 class="zeus-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="zeus-card__desc"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<a class="zeus-text-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read guide', 'zeus' ); ?> <?php echo zeus_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No guides have been published in this category yet.', 'zeus' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
