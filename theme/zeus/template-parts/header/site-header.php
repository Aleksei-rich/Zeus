<?php
/**
 * Site header: logo, primary nav (desktop), phone, consultation CTA,
 * mobile menu toggle.
 */
?>
<header class="zeus-header">
	<div class="zeus-container zeus-header__inner">
		<a class="zeus-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<picture>
				<source srcset="<?php echo esc_url( ZEUS_THEME_URI . '/assets/img/logo-header.webp' ); ?>" type="image/webp">
				<img src="<?php echo esc_url( ZEUS_THEME_URI . '/assets/img/logo-header.png' ); ?>" width="450" height="118" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="zeus-logo__img">
			</picture>
		</a>

		<nav class="zeus-primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'zeus' ); ?>">
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

		<div class="zeus-header__actions">
			<a class="zeus-header__phone" href="<?php echo esc_attr( zeus_phone_number_href() ); ?>">
				<?php echo zeus_icon( 'phone' ); // phpcs:ignore ?>
				<span><?php echo esc_html( zeus_phone_number_display() ); ?></span>
			</a>
			<a class="zeus-btn zeus-btn--primary" href="<?php echo esc_url( zeus_consultation_url() ); ?>">
				<?php esc_html_e( 'Request Free Consultation', 'zeus' ); ?>
			</a>
			<button type="button" class="zeus-menu-toggle" data-zeus-menu-toggle aria-expanded="false" aria-controls="zeus-mobile-nav">
				<span class="zeus-visually-hidden"><?php esc_html_e( 'Open menu', 'zeus' ); ?></span>
				<?php echo zeus_icon( 'menu' ); // phpcs:ignore ?>
			</button>
		</div>
	</div>
</header>
