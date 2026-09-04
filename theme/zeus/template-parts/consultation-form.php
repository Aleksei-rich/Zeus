<?php
/**
 * Request Free Consultation form. Rendering only — validation,
 * sanitization, storage, and notification all live in the zeus-core
 * plugin (theme/plugin boundary: this file just displays the form and
 * any errors zeus-core hands back via zeus_get_form_error_data()).
 */
$zeus_project_types = function_exists( 'zeus_consultation_project_types' )
	? array( '' => __( 'Select a project type…', 'zeus' ) ) + zeus_consultation_project_types()
	: array( '' => __( 'Select a project type…', 'zeus' ) );

$zeus_form_data   = function_exists( 'zeus_get_form_error_data' ) ? zeus_get_form_error_data() : null;
$zeus_errors      = $zeus_form_data['errors'] ?? array();
$zeus_values      = $zeus_form_data['values'] ?? array();
$zeus_action_url  = function_exists( 'admin_url' ) ? add_query_arg( 'action', 'zeus_submit_consultation', admin_url( 'admin-post.php' ) ) : '';

$zeus_field_value = static function ( $name ) use ( $zeus_values ) {
	return isset( $zeus_values[ $name ] ) ? $zeus_values[ $name ] : '';
};
?>
<?php if ( ! empty( $zeus_errors['_form'] ) ) : ?>
	<div class="zeus-form__alert" role="alert"><?php echo esc_html( $zeus_errors['_form'] ); ?></div>
<?php elseif ( $zeus_errors ) : ?>
	<div class="zeus-form__alert" role="alert"><?php esc_html_e( 'Please fix the highlighted fields below.', 'zeus' ); ?></div>
<?php endif; ?>

<form class="zeus-form" method="post" action="<?php echo esc_url( $zeus_action_url ); ?>" enctype="multipart/form-data" novalidate data-zeus-consultation-form>
	<input type="hidden" name="action" value="zeus_submit_consultation">
	<input type="hidden" name="zeus_redirect_to" value="<?php echo esc_url( home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) ) ); ?>">
	<input type="hidden" name="zeus_form_ts" value="<?php echo esc_attr( time() ); ?>">
	<?php wp_nonce_field( 'zeus_submit_consultation', 'zeus_consultation_nonce' ); ?>

	<div class="zeus-visually-hidden" aria-hidden="true">
		<label for="zeus-website"><?php esc_html_e( 'Leave this field empty', 'zeus' ); ?></label>
		<input type="text" id="zeus-website" name="zeus_website" tabindex="-1" autocomplete="off">
	</div>

	<div class="zeus-form__grid zeus-form__grid--2">
		<div class="zeus-form__row">
			<label for="zeus-name"><?php esc_html_e( 'Name', 'zeus' ); ?> <span aria-hidden="true">*</span></label>
			<input type="text" id="zeus-name" name="name" autocomplete="name" required aria-required="true"
				value="<?php echo esc_attr( $zeus_field_value( 'name' ) ); ?>"
				<?php if ( ! empty( $zeus_errors['name'] ) ) : ?>aria-invalid="true" aria-describedby="zeus-name-error"<?php endif; ?>>
			<?php if ( ! empty( $zeus_errors['name'] ) ) : ?><p class="zeus-form__error" id="zeus-name-error"><?php echo esc_html( $zeus_errors['name'] ); ?></p><?php endif; ?>
		</div>
		<div class="zeus-form__row">
			<label for="zeus-phone"><?php esc_html_e( 'Phone', 'zeus' ); ?> <span aria-hidden="true">*</span></label>
			<input type="tel" id="zeus-phone" name="phone" autocomplete="tel" required aria-required="true"
				value="<?php echo esc_attr( $zeus_field_value( 'phone' ) ); ?>"
				<?php if ( ! empty( $zeus_errors['phone'] ) ) : ?>aria-invalid="true" aria-describedby="zeus-phone-error"<?php endif; ?>>
			<?php if ( ! empty( $zeus_errors['phone'] ) ) : ?><p class="zeus-form__error" id="zeus-phone-error"><?php echo esc_html( $zeus_errors['phone'] ); ?></p><?php endif; ?>
		</div>
		<div class="zeus-form__row">
			<label for="zeus-email"><?php esc_html_e( 'Email', 'zeus' ); ?> <span aria-hidden="true">*</span></label>
			<input type="email" id="zeus-email" name="email" autocomplete="email" required aria-required="true"
				value="<?php echo esc_attr( $zeus_field_value( 'email' ) ); ?>"
				<?php if ( ! empty( $zeus_errors['email'] ) ) : ?>aria-invalid="true" aria-describedby="zeus-email-error"<?php endif; ?>>
			<?php if ( ! empty( $zeus_errors['email'] ) ) : ?><p class="zeus-form__error" id="zeus-email-error"><?php echo esc_html( $zeus_errors['email'] ); ?></p><?php endif; ?>
		</div>
		<div class="zeus-form__row">
			<label for="zeus-zip"><?php esc_html_e( 'ZIP Code', 'zeus' ); ?> <span aria-hidden="true">*</span></label>
			<input type="text" id="zeus-zip" name="zip" inputmode="numeric" pattern="[0-9]{5}" autocomplete="postal-code" required aria-required="true"
				value="<?php echo esc_attr( $zeus_field_value( 'zip' ) ); ?>"
				<?php if ( ! empty( $zeus_errors['zip'] ) ) : ?>aria-invalid="true" aria-describedby="zeus-zip-error"<?php endif; ?>>
			<?php if ( ! empty( $zeus_errors['zip'] ) ) : ?><p class="zeus-form__error" id="zeus-zip-error"><?php echo esc_html( $zeus_errors['zip'] ); ?></p><?php endif; ?>
		</div>
	</div>

	<div class="zeus-form__row">
		<label for="zeus-project-type"><?php esc_html_e( 'Project Type (optional)', 'zeus' ); ?></label>
		<select id="zeus-project-type" name="project_type"
			<?php if ( ! empty( $zeus_errors['project_type'] ) ) : ?>aria-invalid="true" aria-describedby="zeus-project-type-error"<?php endif; ?>>
			<?php foreach ( $zeus_project_types as $zeus_value => $zeus_label ) : ?>
				<option value="<?php echo esc_attr( $zeus_value ); ?>" <?php selected( $zeus_field_value( 'project_type' ), $zeus_value ); ?>><?php echo esc_html( $zeus_label ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php if ( ! empty( $zeus_errors['project_type'] ) ) : ?><p class="zeus-form__error" id="zeus-project-type-error"><?php echo esc_html( $zeus_errors['project_type'] ); ?></p><?php endif; ?>
	</div>

	<div class="zeus-form__row">
		<label for="zeus-description"><?php esc_html_e( 'Project Description', 'zeus' ); ?> <span aria-hidden="true">*</span></label>
		<textarea id="zeus-description" name="description" required aria-required="true"
			<?php if ( ! empty( $zeus_errors['description'] ) ) : ?>aria-invalid="true" aria-describedby="zeus-description-error"<?php endif; ?>><?php echo esc_textarea( $zeus_field_value( 'description' ) ); ?></textarea>
		<?php if ( ! empty( $zeus_errors['description'] ) ) : ?><p class="zeus-form__error" id="zeus-description-error"><?php echo esc_html( $zeus_errors['description'] ); ?></p><?php endif; ?>
	</div>

	<div class="zeus-form__row">
		<label for="zeus-uploads"><?php esc_html_e( 'Photos or Plans (optional)', 'zeus' ); ?></label>
		<input type="file" id="zeus-uploads" name="uploads[]" accept=".jpg,.jpeg,.png,.webp,.heic,.heif,.pdf,image/jpeg,image/png,image/webp,image/heic,image/heif,application/pdf" multiple
			aria-describedby="zeus-uploads-note zeus-uploads-status<?php echo ! empty( $zeus_errors['uploads'] ) ? ' zeus-uploads-error' : ''; ?>"
			<?php if ( ! empty( $zeus_errors['uploads'] ) ) : ?>aria-invalid="true"<?php endif; ?>>
		<p class="zeus-form__note" id="zeus-uploads-note"><?php esc_html_e( 'You can attach up to 5 photos or plans. JPG, PNG, WEBP, HEIC/HEIF, or PDF. Maximum 10MB per file and 15MB total.', 'zeus' ); ?></p>
		<p class="zeus-form__note" id="zeus-uploads-status" data-zeus-upload-status aria-live="polite"></p>
		<?php if ( ! empty( $zeus_errors['uploads'] ) ) : ?><p class="zeus-form__error" id="zeus-uploads-error"><?php echo esc_html( $zeus_errors['uploads'] ); ?></p><?php endif; ?>
	</div>

	<p class="zeus-form__note"><?php esc_html_e( 'Required fields are marked with *. We use this information only to prepare your consultation.', 'zeus' ); ?></p>

	<button type="submit" class="zeus-btn zeus-btn--primary zeus-btn--block" data-zeus-submit><?php esc_html_e( 'Request Free Consultation', 'zeus' ); ?></button>
</form>
