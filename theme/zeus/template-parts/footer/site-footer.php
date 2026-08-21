<?php
/**
 * Site footer: brand blurb, nav, service areas, legal line.
 */
$zeus_areas = array( 'Orlando', 'Windermere', 'Winter Garden', 'Horizon West', 'Clermont', 'Dr. Phillips' );
?>
<footer class="zeus-footer">
	<div class="zeus-container zeus-footer__grid">
		<div>
			<p class="zeus-logo"><?php bloginfo( 'name' ); ?></p>
			<p><?php esc_html_e( 'Custom cabinetry and countertops for Orlando and Central Florida. Service-area business — consultations by appointment.', 'zeus' ); ?></p>
			<p>
				<a href="<?php echo esc_attr( zeus_phone_number_href() ); ?>"><?php echo esc_html( zeus_phone_number_display() ); ?></a>
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
			<p>
				<a class="zeus-btn zeus-btn--secondary zeus-btn--on-dark" href="<?php echo esc_url( zeus_consultation_url() ); ?>">
					<?php esc_html_e( 'Request Free Consultation', 'zeus' ); ?>
				</a>
			</p>
		</div>
	</div>

	<div class="zeus-container zeus-footer__bottom">
		<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span>
		<span class="zeus-placeholder-note"><?php esc_html_e( '[Development placeholder: licensing/registration info if applicable]', 'zeus' ); ?></span>
	</div>
</footer>
