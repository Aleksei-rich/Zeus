<?php
/**
 * Request Free Consultation form — structural markup only in this phase.
 * Fields match docs/PROJECT-SPEC.md. Submission handling (native PHP
 * handler + thank-you redirect) is a tracked follow-up task — see
 * docs/TASKS.md — not a form-builder plugin, per architecture priority.
 * Accessible: every field has a real <label>, required fields marked
 * both visually and via the required attribute + aria-required.
 */
$zeus_project_types = array(
	''                 => __( 'Select a project type…', 'zeus' ),
	'kitchen'          => __( 'Kitchen Cabinets', 'zeus' ),
	'bathroom'         => __( 'Bathroom Cabinets & Vanities', 'zeus' ),
	'countertops'      => __( 'Countertops Only', 'zeus' ),
	'closet'           => __( 'Custom Closet', 'zeus' ),
	'laundry-pantry'   => __( 'Laundry & Pantry', 'zeus' ),
	'home-office'      => __( 'Home Office', 'zeus' ),
	'other'            => __( 'Other', 'zeus' ),
);
?>
<form class="zeus-form" method="post" action="">
	<div class="zeus-form__grid zeus-form__grid--2">
		<div class="zeus-form__row">
			<label for="zeus-name"><?php esc_html_e( 'Name', 'zeus' ); ?> <span aria-hidden="true">*</span></label>
			<input type="text" id="zeus-name" name="name" autocomplete="name" required aria-required="true">
		</div>
		<div class="zeus-form__row">
			<label for="zeus-phone"><?php esc_html_e( 'Phone', 'zeus' ); ?> <span aria-hidden="true">*</span></label>
			<input type="tel" id="zeus-phone" name="phone" autocomplete="tel" required aria-required="true">
		</div>
		<div class="zeus-form__row">
			<label for="zeus-email"><?php esc_html_e( 'Email', 'zeus' ); ?> <span aria-hidden="true">*</span></label>
			<input type="email" id="zeus-email" name="email" autocomplete="email" required aria-required="true">
		</div>
		<div class="zeus-form__row">
			<label for="zeus-zip"><?php esc_html_e( 'ZIP Code', 'zeus' ); ?> <span aria-hidden="true">*</span></label>
			<input type="text" id="zeus-zip" name="zip" inputmode="numeric" pattern="[0-9]{5}" autocomplete="postal-code" required aria-required="true">
		</div>
	</div>

	<div class="zeus-form__row">
		<label for="zeus-project-type"><?php esc_html_e( 'Project Type', 'zeus' ); ?> <span aria-hidden="true">*</span></label>
		<select id="zeus-project-type" name="project_type" required aria-required="true">
			<?php foreach ( $zeus_project_types as $zeus_value => $zeus_label ) : ?>
				<option value="<?php echo esc_attr( $zeus_value ); ?>"><?php echo esc_html( $zeus_label ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="zeus-form__row">
		<label for="zeus-description"><?php esc_html_e( 'Project Description', 'zeus' ); ?> <span aria-hidden="true">*</span></label>
		<textarea id="zeus-description" name="description" required aria-required="true"></textarea>
	</div>

	<div class="zeus-form__row">
		<label for="zeus-upload"><?php esc_html_e( 'Photo or Plan (optional)', 'zeus' ); ?></label>
		<input type="file" id="zeus-upload" name="upload" accept="image/*,.pdf">
	</div>

	<p class="zeus-form__note"><?php esc_html_e( 'Required fields are marked with *. We use this information only to prepare your consultation.', 'zeus' ); ?></p>

	<button type="submit" class="zeus-btn zeus-btn--primary zeus-btn--block"><?php esc_html_e( 'Request Free Consultation', 'zeus' ); ?></button>
</form>
