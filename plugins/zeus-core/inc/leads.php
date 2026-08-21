<?php
/**
 * zeus_lead — private storage for Request Free Consultation submissions.
 * Not public, not queryable, not exposed via REST (leads contain PII).
 * Visible only to logged-in editors/admins in wp-admin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_register_lead_post_type() {
	register_post_type(
		'zeus_lead',
		array(
			'labels'              => array(
				'name'          => __( 'Consultation Requests', 'zeus-core' ),
				'singular_name' => __( 'Consultation Request', 'zeus-core' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
			'exclude_from_search' => true,
			'capability_type'     => 'page',
			'menu_icon'           => 'dashicons-email-alt',
			'menu_position'       => 25,
			'supports'            => array( 'title' ),
		)
	);
}
add_action( 'init', 'zeus_register_lead_post_type', 5 );

/* Admin list columns for quick triage. */
function zeus_lead_admin_columns( $columns ) {
	$columns = array(
		'cb'          => $columns['cb'],
		'title'       => __( 'Lead', 'zeus-core' ),
		'zeus_phone'  => __( 'Phone', 'zeus-core' ),
		'zeus_email'  => __( 'Email', 'zeus-core' ),
		'zeus_type'   => __( 'Project Type', 'zeus-core' ),
		'zeus_upload' => __( 'Upload', 'zeus-core' ),
		'date'        => __( 'Submitted', 'zeus-core' ),
	);
	return $columns;
}
add_filter( 'manage_zeus_lead_posts_columns', 'zeus_lead_admin_columns' );

function zeus_lead_admin_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'zeus_phone':
			echo esc_html( get_post_meta( $post_id, 'zeus_lead_phone', true ) );
			break;
		case 'zeus_email':
			echo esc_html( get_post_meta( $post_id, 'zeus_lead_email', true ) );
			break;
		case 'zeus_type':
			echo esc_html( get_post_meta( $post_id, 'zeus_lead_project_type', true ) );
			break;
		case 'zeus_upload':
			$path = get_post_meta( $post_id, 'zeus_lead_upload_path', true );
			if ( $path ) {
				$url = wp_nonce_url(
					admin_url( 'admin-post.php?action=zeus_download_lead_upload&lead_id=' . $post_id ),
					'zeus_download_lead_upload_' . $post_id
				);
				echo '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Download', 'zeus-core' ) . '</a>';
			} else {
				echo '&#8212;';
			}
			break;
	}
}
add_action( 'manage_zeus_lead_posts_custom_column', 'zeus_lead_admin_column_content', 10, 2 );

/**
 * Private, non-web-servable directory for lead uploads. Created once on
 * plugin activation (infrastructure setup, not content seeding — no
 * registry gate needed since re-running is harmless: it just re-confirms
 * the protection files exist).
 */
function zeus_lead_uploads_dir() {
	$uploads = wp_upload_dir();
	return trailingslashit( $uploads['basedir'] ) . 'zeus-private-leads';
}

function zeus_core_setup_private_uploads_dir() {
	$dir = zeus_lead_uploads_dir();
	if ( ! file_exists( $dir ) ) {
		wp_mkdir_p( $dir );
	}

	$htaccess = $dir . '/.htaccess';
	if ( ! file_exists( $htaccess ) ) {
		file_put_contents( $htaccess, "Require all denied\nDeny from all\n" );
	}

	$webconfig = $dir . '/web.config';
	if ( ! file_exists( $webconfig ) ) {
		file_put_contents(
			$webconfig,
			"<configuration>\n  <system.webServer>\n    <authorization>\n      <deny users=\"*\" />\n    </authorization>\n  </system.webServer>\n</configuration>\n"
		);
	}

	$index = $dir . '/index.php';
	if ( ! file_exists( $index ) ) {
		file_put_contents( $index, "<?php\n// Silence is golden.\n" );
	}
}

/**
 * Admin-gated download for a lead's uploaded file — the only way the
 * file is ever served. Never linked publicly.
 */
function zeus_handle_lead_download() {
	$lead_id = isset( $_GET['lead_id'] ) ? absint( $_GET['lead_id'] ) : 0;
	if ( ! $lead_id || ! current_user_can( 'edit_post', $lead_id ) ) {
		wp_die( esc_html__( 'You do not have permission to access this file.', 'zeus-core' ), 403 );
	}
	check_admin_referer( 'zeus_download_lead_upload_' . $lead_id );

	$relative_path = get_post_meta( $lead_id, 'zeus_lead_upload_path', true );
	if ( ! $relative_path ) {
		wp_die( esc_html__( 'No file found for this request.', 'zeus-core' ), 404 );
	}

	$dir  = zeus_lead_uploads_dir();
	$path = $dir . '/' . ltrim( $relative_path, '/' );

	// Defense in depth: resolve and confirm the path stays inside the
	// private directory (blocks any theoretical path-traversal even
	// though $relative_path is always our own generated random name).
	$real_dir  = realpath( $dir );
	$real_path = realpath( $path );
	if ( ! $real_path || ! $real_dir || 0 !== strpos( $real_path, $real_dir ) ) {
		wp_die( esc_html__( 'File not found.', 'zeus-core' ), 404 );
	}

	$filename = get_post_meta( $lead_id, 'zeus_lead_upload_original_name', true );
	$mime     = get_post_meta( $lead_id, 'zeus_lead_upload_mime', true );

	nocache_headers();
	header( 'Content-Type: ' . ( $mime ? $mime : 'application/octet-stream' ) );
	header( 'Content-Disposition: attachment; filename="' . sanitize_file_name( $filename ? $filename : 'upload' ) . '"' );
	header( 'Content-Length: ' . filesize( $real_path ) );
	readfile( $real_path ); // phpcs:ignore
	exit;
}
add_action( 'admin_post_zeus_download_lead_upload', 'zeus_handle_lead_download' );
