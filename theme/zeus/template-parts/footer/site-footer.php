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
				<img src="<?php echo esc_url( ZEUS_THEME_URI . '/assets/img/logo-footer-brand.png' ); ?>" width="1429" height="578" alt="<?php esc_attr_e( 'ZEUS Cabinets & Countertops', 'zeus' ); ?>" class="zeus-logo__img">
			</a>
			<p><?php esc_html_e( 'In-stock and custom cabinetry, countertops, delivery and installation for Orlando & Central Florida. Service-area business — consultations by appointment.', 'zeus' ); ?></p>
			<?php if ( $zeus_social ) : ?>
				<?php $zeus_social_labels = zeus_social_labels(); ?>
				<div class="zeus-footer__social">
					<p class="zeus-footer__heading"><?php esc_html_e( 'Follow Us', 'zeus' ); ?></p>
					<ul class="zeus-footer__social-list">
						<?php foreach ( $zeus_social as $zeus_platform => $zeus_url ) : ?>
							<?php if ( ! empty( $zeus_url ) && ! empty( $zeus_social_labels[ $zeus_platform ] ) ) : ?>
								<li class="zeus-footer__social-item">
									<a class="zeus-footer__social-link" href="<?php echo esc_url( $zeus_url ); ?>" target="_blank" rel="noopener noreferrer">
										<?php echo zeus_icon( $zeus_platform ); // phpcs:ignore WordPress.Security.EscapeOutput -- fixed, hand-authored inline SVG, no user input. ?>
										<span><?php echo esc_html( $zeus_social_labels[ $zeus_platform ] ); ?></span>
									</a>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
			<p>
				<a href="<?php echo esc_attr( zeus_phone_number_href() ); ?>"><?php echo esc_html( zeus_phone_number_display() ); ?></a><br>
				<a href="mailto:<?php echo esc_attr( zeus_email_address() ); ?>"><?php echo esc_html( zeus_email_address() ); ?></a>
			</p>
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
		<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'zeus' ); ?></a>
	</div>
</footer>
