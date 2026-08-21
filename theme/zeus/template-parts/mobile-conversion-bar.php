<?php
/**
 * Persistent mobile conversion bar: Call | Consultation. Real <a> links
 * (works without JS), fixed via CSS only. Hidden at the lg breakpoint
 * where the header's own CTA/phone are visible instead.
 * See docs/DESIGN-SYSTEM.md "Mobile conversion bar".
 */
?>
<div class="zeus-mobile-bar" role="navigation" aria-label="<?php esc_attr_e( 'Quick contact', 'zeus' ); ?>">
	<a href="<?php echo esc_attr( zeus_phone_number_href() ); ?>">
		<?php echo zeus_icon( 'phone' ); // phpcs:ignore ?>
		<?php esc_html_e( 'Call', 'zeus' ); ?>
	</a>
	<a href="<?php echo esc_url( zeus_consultation_url() ); ?>">
		<?php esc_html_e( 'Consultation', 'zeus' ); ?>
	</a>
</div>
