<?php
/**
 * CTA band. Usage:
 * get_template_part( 'components/cta-section', null, array(
 *     'heading' => '...', 'text' => '...',
 * ) );
 */
$zeus_heading = $args['heading'] ?? __( 'Ready to start your project?', 'zeus' );
$zeus_text    = $args['text'] ?? __( 'Request a free consultation and get a plan tailored to your space.', 'zeus' );
?>
<div class="zeus-cta">
	<h2><?php echo esc_html( $zeus_heading ); ?></h2>
	<p><?php echo esc_html( $zeus_text ); ?></p>
	<div class="zeus-cta__actions">
		<?php
		get_template_part(
			'components/button',
			null,
			array(
				'label'   => __( 'Request Free Consultation', 'zeus' ),
				'url'     => zeus_consultation_url(),
				'variant' => 'primary',
			)
		);
		get_template_part(
			'components/button',
			null,
			array(
				'label'   => zeus_phone_number_display(),
				'url'     => zeus_phone_number_href(),
				'variant' => 'secondary',
				'on_dark' => true,
			)
		);
		?>
	</div>
</div>
