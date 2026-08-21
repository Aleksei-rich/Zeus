<?php
/**
 * Tools > ZEUS Setup — the non-WP-CLI way to explicitly trigger initial
 * content seeding. Requires manage_options + a valid nonce; never runs
 * on its own. This is the "explicit controlled setup action" required
 * by the seeding safety audit for environments without shell/WP-CLI
 * access (e.g. some managed hosting).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_core_register_tools_page() {
	add_management_page(
		__( 'ZEUS Setup', 'zeus-core' ),
		__( 'ZEUS Setup', 'zeus-core' ),
		'manage_options',
		'zeus-setup',
		'zeus_core_render_tools_page'
	);
}
add_action( 'admin_menu', 'zeus_core_register_tools_page' );

function zeus_core_render_tools_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'zeus-core' ) );
	}

	$results = null;

	if ( isset( $_POST['zeus_run_seed'] ) ) {
		check_admin_referer( 'zeus_run_seed_action', 'zeus_run_seed_nonce' );
		$results = zeus_seed_all();
	}

	$registry = zeus_seed_registry_get();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'ZEUS Setup', 'zeus-core' ); ?></h1>
		<p><?php esc_html_e( 'Creates the standard ZEUS pages, cabinet collections, taxonomy terms, and navigation menus this site expects — but only the ones that don\'t already exist and have never been seeded before. Editing or deleting seeded content afterward is safe: this tool will never recreate something it already created once, even if you delete it.', 'zeus-core' ); ?></p>

		<?php if ( null !== $results ) : ?>
			<div class="notice notice-success">
				<p><strong><?php esc_html_e( 'Setup run complete.', 'zeus-core' ); ?></strong></p>
				<ul style="list-style:disc;margin-left:1.5em;">
					<?php foreach ( $results as $category => $created ) : ?>
						<li>
							<?php echo esc_html( ucfirst( $category ) ); ?>:
							<?php echo $created ? esc_html( implode( ', ', $created ) ) : esc_html__( 'nothing new (already seeded)', 'zeus-core' ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'zeus_run_seed_action', 'zeus_run_seed_nonce' ); ?>
			<p>
				<button type="submit" name="zeus_run_seed" value="1" class="button button-primary">
					<?php esc_html_e( 'Run Initial Content Setup', 'zeus-core' ); ?>
				</button>
			</p>
		</form>

		<h2><?php esc_html_e( 'Seed registry', 'zeus-core' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Everything below has been permanently recorded as seeded, and will be skipped on future runs even if deleted.', 'zeus-core' ); ?></p>
		<?php foreach ( $registry as $category => $entries ) : ?>
			<p><strong><?php echo esc_html( ucfirst( $category ) ); ?></strong> (<?php echo count( $entries ); ?>): <?php echo esc_html( implode( ', ', array_keys( $entries ) ) ); ?></p>
		<?php endforeach; ?>

		<p class="description"><?php esc_html_e( 'Equivalent WP-CLI command: wp zeus seed', 'zeus-core' ); ?></p>
	</div>
	<?php
}
