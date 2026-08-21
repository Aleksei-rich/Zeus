<?php
/**
 * Router for `php -S` (PHP's built-in dev server), used only by
 * tools/start-local-env.ps1. PHP's built-in server does not honor
 * .htaccess, so without this router the private lead-uploads
 * directory (protected by .htaccess/web.config on real Apache/IIS
 * hosting) would be directly downloadable locally. This router closes
 * that local-only gap by denying the same path outright, then falls
 * through to normal WordPress request handling for everything else.
 */

$uri = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );

if ( preg_match( '#^/wp-content/uploads/zeus-private-leads/#', $uri ) ) {
	http_response_code( 403 );
	header( 'Content-Type: text/plain' );
	echo "Forbidden\n";
	return true;
}

// Let the built-in server handle real files (CSS/JS/images) directly;
// everything else goes through WordPress as normal.
$file = __DIR__ . '/../.localenv/wordpress' . $uri;
if ( $uri !== '/' && file_exists( $file ) && ! is_dir( $file ) ) {
	return false;
}

require __DIR__ . '/../.localenv/wordpress/index.php';
