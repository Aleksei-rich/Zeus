<?php
/**
 * Breadcrumb trail — matches the URL hierarchy documented in
 * docs/SITE-ARCHITECTURE.md, and doubles as the data source for the
 * BreadcrumbList structured data in inc/seo.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns an ordered list of ['label' => ..., 'url' => ... or null for current].
 */
function zeus_get_breadcrumb_trail() {
	$trail = array( array( 'label' => __( 'Home', 'zeus' ), 'url' => home_url( '/' ) ) );

	if ( is_front_page() ) {
		return $trail;
	}

	if ( is_singular( 'cabinet_collection' ) ) {
		$trail[] = array( 'label' => __( 'Cabinet Styles', 'zeus' ), 'url' => get_post_type_archive_link( 'cabinet_collection' ) );
		$trail[] = array( 'label' => get_the_title(), 'url' => null );
	} elseif ( is_post_type_archive( 'cabinet_collection' ) ) {
		$trail[] = array( 'label' => __( 'Cabinet Styles', 'zeus' ), 'url' => null );
	} elseif ( is_singular( 'project' ) ) {
		$trail[] = array( 'label' => __( 'Portfolio', 'zeus' ), 'url' => get_post_type_archive_link( 'project' ) );
		$trail[] = array( 'label' => get_the_title(), 'url' => null );
	} elseif ( is_post_type_archive( 'project' ) ) {
		$trail[] = array( 'label' => __( 'Portfolio', 'zeus' ), 'url' => null );
	} elseif ( is_singular( 'page' ) ) {
		global $post;
		if ( $post && $post->post_parent ) {
			$ancestors = array_reverse( get_post_ancestors( $post ) );
			foreach ( $ancestors as $ancestor_id ) {
				$trail[] = array( 'label' => get_the_title( $ancestor_id ), 'url' => get_permalink( $ancestor_id ) );
			}
		}
		$trail[] = array( 'label' => get_the_title(), 'url' => null );
	} elseif ( is_home() ) {
		$trail[] = array( 'label' => __( 'Blog', 'zeus' ), 'url' => null );
	} elseif ( is_singular( 'post' ) ) {
		$blog_page_id = get_option( 'page_for_posts' );
		if ( $blog_page_id ) {
			$trail[] = array( 'label' => get_the_title( $blog_page_id ), 'url' => get_permalink( $blog_page_id ) );
		}
		$trail[] = array( 'label' => get_the_title(), 'url' => null );
	} elseif ( is_404() ) {
		$trail[] = array( 'label' => __( 'Page Not Found', 'zeus' ), 'url' => null );
	}

	return $trail;
}

function zeus_render_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	$trail = zeus_get_breadcrumb_trail();
	if ( count( $trail ) < 2 ) {
		return;
	}
	?>
	<nav class="zeus-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'zeus' ); ?>">
		<ol class="zeus-breadcrumbs__list">
			<?php foreach ( $trail as $i => $crumb ) : ?>
				<li class="zeus-breadcrumbs__item">
					<?php if ( $crumb['url'] ) : ?>
						<a href="<?php echo esc_url( $crumb['url'] ); ?>"><?php echo esc_html( $crumb['label'] ); ?></a>
					<?php else : ?>
						<span aria-current="page"><?php echo esc_html( $crumb['label'] ); ?></span>
					<?php endif; ?>
					<?php if ( $i < count( $trail ) - 1 ) : ?>
						<span class="zeus-breadcrumbs__sep" aria-hidden="true"><?php echo zeus_icon( 'chevron' ); // phpcs:ignore ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</nav>
	<?php
}
