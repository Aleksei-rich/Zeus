<?php
/**
 * Structured custom fields for cabinet_collection and project, implemented
 * with native register_post_meta() + hand-built meta boxes. No ACF or
 * other field-management plugin — see docs/DECISIONS.md (2026-08-21,
 * "Phase 2: no plugins installed...").
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------------------------------------------------------------------
 * REST-visible meta registration (needed for the block editor + any
 * future headless/API use, and to keep sanitization centralized).
 * ------------------------------------------------------------------ */
function zeus_register_meta_fields() {

	$text_field = array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback'     => 'zeus_meta_auth_callback',
	);

	$textarea_field = array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'wp_kses_post',
		'auth_callback'     => 'zeus_meta_auth_callback',
	);

	$bool_field = array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'boolean',
		'default'           => false,
		'auth_callback'     => 'zeus_meta_auth_callback',
	);

	$int_field = array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'integer',
		'sanitize_callback' => 'absint',
		'auth_callback'     => 'zeus_meta_auth_callback',
	);

	$gallery_field = array(
		'show_in_rest'      => array(
			'schema' => array(
				'type'  => 'array',
				'items' => array( 'type' => 'integer' ),
			),
		),
		'single'            => true,
		'type'              => 'array',
		'sanitize_callback' => 'zeus_sanitize_id_array',
		'auth_callback'     => 'zeus_meta_auth_callback',
	);

	// cabinet_collection
	register_post_meta( 'cabinet_collection', 'zeus_profile_type', $text_field );
	register_post_meta( 'cabinet_collection', 'zeus_construction_notes', $textarea_field );
	register_post_meta( 'cabinet_collection', 'zeus_gallery', $gallery_field );
	register_post_meta( 'cabinet_collection', 'zeus_is_featured', $bool_field );

	// project
	register_post_meta( 'project', 'zeus_location', $text_field );
	register_post_meta( 'project', 'zeus_design_decisions', $textarea_field );
	register_post_meta( 'project', 'zeus_gallery', $gallery_field );
	register_post_meta( 'project', 'zeus_before_image', $int_field );
	register_post_meta( 'project', 'zeus_after_image', $int_field );
	register_post_meta( 'project', 'zeus_project_status', $text_field ); // 'completed' | 'concept'
	register_post_meta( 'project', 'zeus_is_featured', $bool_field );
	register_post_meta( 'project', 'zeus_cta_variant', $text_field );

	// Shared SEO fields (cabinet_collection, project, page, post)
	foreach ( array( 'cabinet_collection', 'project', 'page', 'post' ) as $seo_post_type ) {
		register_post_meta( $seo_post_type, 'zeus_seo_title', $text_field );
		register_post_meta( $seo_post_type, 'zeus_seo_description', $text_field );
	}
}
add_action( 'init', 'zeus_register_meta_fields' );

function zeus_meta_auth_callback() {
	return current_user_can( 'edit_posts' );
}

function zeus_sanitize_id_array( $value ) {
	if ( ! is_array( $value ) ) {
		return array();
	}
	return array_values( array_filter( array_map( 'absint', $value ) ) );
}

/* ---------------------------------------------------------------------
 * Meta boxes (classic UI — renders below the block editor).
 * ------------------------------------------------------------------ */
function zeus_add_meta_boxes() {
	add_meta_box( 'zeus_collection_fields', __( 'Collection Details', 'zeus' ), 'zeus_render_collection_meta_box', 'cabinet_collection', 'normal', 'high' );
	add_meta_box( 'zeus_project_fields', __( 'Project Details', 'zeus' ), 'zeus_render_project_meta_box', 'project', 'normal', 'high' );

	foreach ( array( 'cabinet_collection', 'project', 'page', 'post' ) as $seo_post_type ) {
		add_meta_box( 'zeus_seo_fields', __( 'SEO', 'zeus' ), 'zeus_render_seo_meta_box', $seo_post_type, 'normal', 'default' );
	}
}
add_action( 'add_meta_boxes', 'zeus_add_meta_boxes' );

function zeus_render_collection_meta_box( $post ) {
	wp_nonce_field( 'zeus_save_collection_meta', 'zeus_collection_meta_nonce' );
	$profile_type = get_post_meta( $post->ID, 'zeus_profile_type', true );
	$notes        = get_post_meta( $post->ID, 'zeus_construction_notes', true );
	$featured     = get_post_meta( $post->ID, 'zeus_is_featured', true );
	$gallery      = (array) get_post_meta( $post->ID, 'zeus_gallery', true );
	?>
	<p>
		<label for="zeus_profile_type"><strong><?php esc_html_e( 'Profile type', 'zeus' ); ?></strong></label><br>
		<input type="text" id="zeus_profile_type" name="zeus_profile_type" class="widefat" value="<?php echo esc_attr( $profile_type ); ?>" placeholder="e.g. Slim Shaker (narrow-rail)">
	</p>
	<p>
		<label for="zeus_construction_notes"><strong><?php esc_html_e( 'Material / construction notes', 'zeus' ); ?></strong></label><br>
		<textarea id="zeus_construction_notes" name="zeus_construction_notes" class="widefat" rows="4"><?php echo esc_textarea( $notes ); ?></textarea>
	</p>
	<p>
		<label><input type="checkbox" name="zeus_is_featured" value="1" <?php checked( $featured, true ); ?>> <?php esc_html_e( 'Feature on homepage', 'zeus' ); ?></label>
	</p>
	<?php zeus_render_gallery_field( 'zeus_gallery', $gallery, __( 'Collection gallery', 'zeus' ) ); ?>
	<p class="description"><?php esc_html_e( 'Finishes are assigned via the Finish taxonomy panel. Short description = Excerpt. Full description = main editor content.', 'zeus' ); ?></p>
	<?php
}

function zeus_render_project_meta_box( $post ) {
	wp_nonce_field( 'zeus_save_project_meta', 'zeus_project_meta_nonce' );
	$location   = get_post_meta( $post->ID, 'zeus_location', true );
	$decisions  = get_post_meta( $post->ID, 'zeus_design_decisions', true );
	$status     = get_post_meta( $post->ID, 'zeus_project_status', true );
	$featured   = get_post_meta( $post->ID, 'zeus_is_featured', true );
	$cta        = get_post_meta( $post->ID, 'zeus_cta_variant', true );
	$before_id  = (int) get_post_meta( $post->ID, 'zeus_before_image', true );
	$after_id   = (int) get_post_meta( $post->ID, 'zeus_after_image', true );
	$gallery    = (array) get_post_meta( $post->ID, 'zeus_gallery', true );
	if ( '' === $status ) {
		$status = 'concept';
	}
	?>
	<p>
		<strong><?php esc_html_e( 'Project status', 'zeus' ); ?></strong><br>
		<label><input type="radio" name="zeus_project_status" value="completed" <?php checked( $status, 'completed' ); ?>> <?php esc_html_e( 'Completed project (real, finished work)', 'zeus' ); ?></label><br>
		<label><input type="radio" name="zeus_project_status" value="concept" <?php checked( $status, 'concept' ); ?>> <?php esc_html_e( '3D design concept (not yet built)', 'zeus' ); ?></label>
		<br><span class="description"><?php esc_html_e( 'Required. This controls the label shown on the frontend — a concept must never display as a completed project.', 'zeus' ); ?></span>
	</p>
	<p>
		<label for="zeus_location"><strong><?php esc_html_e( 'Location (e.g. "Windermere, FL")', 'zeus' ); ?></strong></label><br>
		<input type="text" id="zeus_location" name="zeus_location" class="widefat" value="<?php echo esc_attr( $location ); ?>">
	</p>
	<p>
		<label for="zeus_design_decisions"><strong><?php esc_html_e( 'Design decisions', 'zeus' ); ?></strong></label><br>
		<textarea id="zeus_design_decisions" name="zeus_design_decisions" class="widefat" rows="4"><?php echo esc_textarea( $decisions ); ?></textarea>
	</p>
	<p>
		<label><input type="checkbox" name="zeus_is_featured" value="1" <?php checked( $featured, true ); ?>> <?php esc_html_e( 'Feature on homepage', 'zeus' ); ?></label>
	</p>
	<p>
		<label for="zeus_cta_variant"><strong><?php esc_html_e( 'CTA override (optional)', 'zeus' ); ?></strong></label><br>
		<input type="text" id="zeus_cta_variant" name="zeus_cta_variant" class="widefat" value="<?php echo esc_attr( $cta ); ?>">
	</p>
	<?php
	zeus_render_single_image_field( 'zeus_before_image', $before_id, __( 'Before image', 'zeus' ) );
	zeus_render_single_image_field( 'zeus_after_image', $after_id, __( 'After image', 'zeus' ) );
	zeus_render_gallery_field( 'zeus_gallery', $gallery, __( 'Project gallery', 'zeus' ) );
	?>
	<p class="description"><?php esc_html_e( 'Project type, service area, cabinetry style, and countertop material are assigned via their taxonomy panels. Description = main editor content.', 'zeus' ); ?></p>
	<?php
}

function zeus_render_seo_meta_box( $post ) {
	wp_nonce_field( 'zeus_save_seo_meta', 'zeus_seo_meta_nonce' );
	$title = get_post_meta( $post->ID, 'zeus_seo_title', true );
	$desc  = get_post_meta( $post->ID, 'zeus_seo_description', true );
	?>
	<p>
		<label for="zeus_seo_title"><strong><?php esc_html_e( 'SEO title override (optional)', 'zeus' ); ?></strong></label><br>
		<input type="text" id="zeus_seo_title" name="zeus_seo_title" class="widefat" value="<?php echo esc_attr( $title ); ?>">
	</p>
	<p>
		<label for="zeus_seo_description"><strong><?php esc_html_e( 'Meta description', 'zeus' ); ?></strong></label><br>
		<textarea id="zeus_seo_description" name="zeus_seo_description" class="widefat" rows="2" maxlength="160"><?php echo esc_textarea( $desc ); ?></textarea>
	</p>
	<?php
}

function zeus_render_single_image_field( $name, $attachment_id, $label ) {
	$url = $attachment_id ? wp_get_attachment_image_url( $attachment_id, 'thumbnail' ) : '';
	?>
	<div class="zeus-image-field" data-target="<?php echo esc_attr( $name ); ?>">
		<p><strong><?php echo esc_html( $label ); ?></strong></p>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" class="zeus-image-field__input" value="<?php echo esc_attr( $attachment_id ); ?>">
		<div class="zeus-image-field__preview">
			<?php if ( $url ) : ?>
				<img src="<?php echo esc_url( $url ); ?>" style="max-width:100px;height:auto;display:block;">
			<?php endif; ?>
		</div>
		<button type="button" class="button zeus-image-field__select"><?php esc_html_e( 'Select image', 'zeus' ); ?></button>
		<button type="button" class="button zeus-image-field__clear"><?php esc_html_e( 'Clear', 'zeus' ); ?></button>
	</div>
	<?php
}

function zeus_render_gallery_field( $name, $ids, $label ) {
	?>
	<div class="zeus-gallery-field" data-target="<?php echo esc_attr( $name ); ?>">
		<p><strong><?php echo esc_html( $label ); ?></strong></p>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" class="zeus-gallery-field__input" value="<?php echo esc_attr( implode( ',', array_map( 'absint', $ids ) ) ); ?>">
		<div class="zeus-gallery-field__preview">
			<?php foreach ( $ids as $id ) :
				$url = wp_get_attachment_image_url( $id, 'thumbnail' );
				if ( ! $url ) {
					continue;
				}
				?>
				<img src="<?php echo esc_url( $url ); ?>" style="max-width:80px;height:auto;display:inline-block;margin:2px;">
			<?php endforeach; ?>
		</div>
		<button type="button" class="button zeus-gallery-field__select"><?php esc_html_e( 'Edit gallery', 'zeus' ); ?></button>
	</div>
	<?php
}

/* ---------------------------------------------------------------------
 * Save handlers — nonce + capability checked, per
 * .claude/rules/wordpress-development.md.
 * ------------------------------------------------------------------ */
function zeus_save_post_meta( $post_id, $post ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( 'cabinet_collection' === $post->post_type
		&& isset( $_POST['zeus_collection_meta_nonce'] )
		&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['zeus_collection_meta_nonce'] ) ), 'zeus_save_collection_meta' )
	) {
		update_post_meta( $post_id, 'zeus_profile_type', isset( $_POST['zeus_profile_type'] ) ? sanitize_text_field( wp_unslash( $_POST['zeus_profile_type'] ) ) : '' );
		update_post_meta( $post_id, 'zeus_construction_notes', isset( $_POST['zeus_construction_notes'] ) ? wp_kses_post( wp_unslash( $_POST['zeus_construction_notes'] ) ) : '' );
		update_post_meta( $post_id, 'zeus_is_featured', ! empty( $_POST['zeus_is_featured'] ) );
		update_post_meta( $post_id, 'zeus_gallery', isset( $_POST['zeus_gallery'] ) ? zeus_sanitize_id_csv( wp_unslash( $_POST['zeus_gallery'] ) ) : array() );
	}

	if ( 'project' === $post->post_type
		&& isset( $_POST['zeus_project_meta_nonce'] )
		&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['zeus_project_meta_nonce'] ) ), 'zeus_save_project_meta' )
	) {
		update_post_meta( $post_id, 'zeus_location', isset( $_POST['zeus_location'] ) ? sanitize_text_field( wp_unslash( $_POST['zeus_location'] ) ) : '' );
		update_post_meta( $post_id, 'zeus_design_decisions', isset( $_POST['zeus_design_decisions'] ) ? wp_kses_post( wp_unslash( $_POST['zeus_design_decisions'] ) ) : '' );
		$status = isset( $_POST['zeus_project_status'] ) ? sanitize_text_field( wp_unslash( $_POST['zeus_project_status'] ) ) : 'concept';
		update_post_meta( $post_id, 'zeus_project_status', in_array( $status, array( 'completed', 'concept' ), true ) ? $status : 'concept' );
		update_post_meta( $post_id, 'zeus_is_featured', ! empty( $_POST['zeus_is_featured'] ) );
		update_post_meta( $post_id, 'zeus_cta_variant', isset( $_POST['zeus_cta_variant'] ) ? sanitize_text_field( wp_unslash( $_POST['zeus_cta_variant'] ) ) : '' );
		update_post_meta( $post_id, 'zeus_before_image', isset( $_POST['zeus_before_image'] ) ? absint( $_POST['zeus_before_image'] ) : 0 );
		update_post_meta( $post_id, 'zeus_after_image', isset( $_POST['zeus_after_image'] ) ? absint( $_POST['zeus_after_image'] ) : 0 );
		update_post_meta( $post_id, 'zeus_gallery', isset( $_POST['zeus_gallery'] ) ? zeus_sanitize_id_csv( wp_unslash( $_POST['zeus_gallery'] ) ) : array() );
	}

	if ( isset( $_POST['zeus_seo_meta_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['zeus_seo_meta_nonce'] ) ), 'zeus_save_seo_meta' ) ) {
		update_post_meta( $post_id, 'zeus_seo_title', isset( $_POST['zeus_seo_title'] ) ? sanitize_text_field( wp_unslash( $_POST['zeus_seo_title'] ) ) : '' );
		update_post_meta( $post_id, 'zeus_seo_description', isset( $_POST['zeus_seo_description'] ) ? sanitize_text_field( wp_unslash( $_POST['zeus_seo_description'] ) ) : '' );
	}
}
add_action( 'save_post', 'zeus_save_post_meta', 10, 2 );

function zeus_sanitize_id_csv( $csv ) {
	if ( ! is_string( $csv ) || '' === $csv ) {
		return array();
	}
	return array_values( array_filter( array_map( 'absint', explode( ',', $csv ) ) ) );
}
