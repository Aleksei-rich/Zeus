<?php
/**
 * Mobile nav drawer. Hidden off-canvas by default (assets/js/main.js
 * toggles data-open); degrades to a normal in-flow list if JS fails,
 * since it's still reachable in the DOM/tab order.
 */
?>
<div id="zeus-mobile-nav" class="zeus-mobile-nav" data-open="false">
	<div class="zeus-mobile-nav__header">
		<button type="button" class="zeus-menu-toggle" data-zeus-menu-close>
			<span class="zeus-visually-hidden"><?php esc_html_e( 'Close menu', 'zeus' ); ?></span>
			<?php echo zeus_icon( 'close' ); // phpcs:ignore ?>
		</button>
	</div>
	<nav aria-label="<?php esc_attr_e( 'Mobile', 'zeus' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'depth'          => 2,
				'fallback_cb'    => false,
			)
		);
		?>
	</nav>
	<p style="margin-top:2rem;">
		<a class="zeus-btn zeus-btn--primary zeus-btn--block" href="<?php echo esc_url( zeus_consultation_url() ); ?>">
			<?php esc_html_e( 'Request Free Consultation', 'zeus' ); ?>
		</a>
	</p>
</div>
