<?php
/**
 * Tasteful floating consultation CTA.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wp_footer',
	function () {
		if ( is_page( 'consultation' ) || is_page( 'thank-you' ) ) {
			return;
		}
		?>
		<a class="zeus-sticky-consultation" href="<?php echo esc_url( zeus_consultation_url() ); ?>" aria-label="<?php esc_attr_e( 'Request a free consultation', 'zeus' ); ?>">
			<span class="zeus-sticky-consultation__desktop"><?php esc_html_e( 'Free Consultation', 'zeus' ); ?></span>
			<span class="zeus-sticky-consultation__mobile"><?php esc_html_e( 'Consultation', 'zeus' ); ?></span>
		</a>
		<style>
			.zeus-sticky-consultation{position:fixed;right:22px;bottom:22px;z-index:90;display:inline-flex;align-items:center;justify-content:center;min-height:48px;padding:0 22px;border-radius:999px;background:#0b1f33;color:#fff!important;text-decoration:none;font-weight:700;letter-spacing:.01em;box-shadow:0 10px 28px rgba(11,31,51,.22);border:1px solid rgba(255,255,255,.18);transition:transform .18s ease,box-shadow .18s ease,background .18s ease}.zeus-sticky-consultation:hover,.zeus-sticky-consultation:focus-visible{transform:translateY(-2px);box-shadow:0 14px 34px rgba(11,31,51,.28);background:#142d47}.zeus-sticky-consultation__mobile{display:none}@media (max-width:700px){.zeus-sticky-consultation{right:12px;bottom:12px;min-height:44px;padding:0 16px;font-size:14px;max-width:calc(100vw - 24px)}.zeus-sticky-consultation__desktop{display:none}.zeus-sticky-consultation__mobile{display:inline}}@media (prefers-reduced-motion:reduce){.zeus-sticky-consultation{transition:none}}
		</style>
		<?php
	},
	30
);
