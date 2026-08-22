<?php
/**
 * Site footer: brand blurb, nav, service areas, legal line.
 */
$zeus_areas  = array( 'Orlando', 'Windermere', 'Winter Garden', 'Horizon West', 'Clermont', 'Dr. Phillips' );
$zeus_social = zeus_social_links();
?>
<footer class="zeus-footer">
	<div class="zeus-container zeus-footer__grid">
		<div class="zeus-footer__brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="zeus-logo">
				<picture>
					<source srcset="<?php echo esc_url( ZEUS_THEME_URI . '/assets/img/logo-footer.webp' ); ?>" type="image/webp">
					<img src="<?php echo esc_url( ZEUS_THEME_URI . '/assets/img/logo-footer.png' ); ?>" width="981" height="240" alt="ZEUS" class="zeus-logo__img">
				</picture>
				<span class="zeus-logo__subtitle"><?php esc_html_e( 'Cabinets + Countertops', 'zeus' ); ?></span>
			</a>
			<p><?php esc_html_e( 'Custom cabinetry and countertops for Orlando and Central Florida. Service-area business — consultations by appointment.', 'zeus' ); ?></p>
			<p>
				<a href="<?php echo esc_attr( zeus_phone_number_href() ); ?>"><?php echo esc_html( zeus_phone_number_display() ); ?></a><br>
				<a href="mailto:<?php echo esc_attr( zeus_email_address() ); ?>"><?php echo esc_html( zeus_email_address() ); ?></a>
			</p>
			<?php if ( $zeus_social ) : ?>
				<p class="zeus-footer__social">
					<?php if ( ! empty( $zeus_social['facebook'] ) ) : ?>
						<a href="<?php echo esc_url( $zeus_social['facebook'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Facebook', 'zeus' ); ?></a>
					<?php endif; ?>
					<?php if ( ! empty( $zeus_social['instagram'] ) ) : ?>
						<a href="<?php echo esc_url( $zeus_social['instagram'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Instagram', 'zeus' ); ?></a>
					<?php endif; ?>
				</p>
			<?php endif; ?>
		</div>

		<div>
			<p class="zeus-footer__heading"><?php esc_html_e( 'Explore', 'zeus' ); ?></p>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</div>

		<div>
			<p class="zeus-footer__heading"><?php esc_html_e( 'Service Area', 'zeus' ); ?></p>
			<ul>
				<?php foreach ( $zeus_areas as $zeus_area ) : ?>
					<li><?php echo esc_html( $zeus_area ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div>
			<p class="zeus-footer__heading"><?php esc_html_e( 'Get Started', 'zeus' ); ?></p>
			<p><?php echo esc_html( zeus_business_hours() ); ?></p>
			<p>
				<a class="zeus-btn zeus-btn--secondary zeus-btn--on-dark" href="<?php echo esc_url( zeus_consultation_url() ); ?>">
					<?php esc_html_e( 'Request Free Consultation', 'zeus' ); ?>
				</a>
			</p>
		</div>
	</div>

	<div class="zeus-container zeus-footer__bottom">
		<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span>
	</div>
</footer>
