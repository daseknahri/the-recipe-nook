<?php
/**
 * RC Page Cache — early-serve drop-in (wp-content/advanced-cache.php).
 *
 * WordPress includes this file in wp-settings.php when WP_CACHE is on, BEFORE the database,
 * plugins and mu-plugins load. It serves the exact cache files written by the RC Page Cache
 * mu-plugin (same on-disk format: 3-byte status prefix + HTML), so a warm page costs a file
 * read instead of a full WordPress bootstrap. It NEVER stores: storing, purging and every
 * other rule stay with the mu-plugin, which also remains the fallback server whenever this
 * drop-in declines (stale file, bypassed request, missing constant).
 *
 * The bypass rules and the cache-key recipe below intentionally mirror rcpc_bypass() and
 * rcpc_file() in mu-plugins/rc-page-cache.php — including core's wp_is_mobile() test, which
 * the mu-plugin uses for the device variant. Keep the two in sync when either changes.
 */

if ( ! defined( 'ABSPATH' ) ) { return; }

/* Only ever serve a plain front-end GET; everything else falls through to WordPress. */
if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'GET' !== strtoupper( (string) $_SERVER['REQUEST_METHOD'] ) ) { return; }
if ( defined( 'WP_ADMIN' ) && WP_ADMIN ) { return; }
if ( ! isset( $_SERVER['REQUEST_URI'] ) ) { return; }

$rcac_uri = (string) $_SERVER['REQUEST_URI'];
if ( false !== strpos( $rcac_uri, '/wp-' ) || false !== strpos( $rcac_uri, 'preview' ) || false !== strpos( $rcac_uri, 'feed' ) ) { return; }
if ( isset( $_SERVER['QUERY_STRING'] ) && '' !== (string) $_SERVER['QUERY_STRING'] ) { return; }
foreach ( array_keys( $_COOKIE ) as $rcac_c ) {
	if ( preg_match( '/^(wordpress_logged_in|wp-postpass|comment_author)/', (string) $rcac_c ) ) { return; }
}
if ( isset( $_COOKIE['wordpress_test_cookie'] ) ) { return; }

/* Device variant — core wp_is_mobile()'s test, verbatim (the function itself is not loaded yet). */
$rcac_ua       = isset( $_SERVER['HTTP_USER_AGENT'] ) ? (string) $_SERVER['HTTP_USER_AGENT'] : '';
$rcac_mobile   = '' !== $rcac_ua && ( false !== strpos( $rcac_ua, 'Mobile' ) || false !== strpos( $rcac_ua, 'Android' )
	|| false !== strpos( $rcac_ua, 'Silk/' ) || false !== strpos( $rcac_ua, 'Kindle' )
	|| false !== strpos( $rcac_ua, 'BlackBerry' ) || false !== strpos( $rcac_ua, 'Opera Mini' )
	|| false !== strpos( $rcac_ua, 'Opera Mobi' ) );
$rcac_host     = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( (string) $_SERVER['HTTP_HOST'] ) : '';
$rcac_dir      = defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR : ABSPATH . 'wp-content';
$rcac_file     = $rcac_dir . '/cache/rc-page/' . md5( $rcac_host . $rcac_uri . ( $rcac_mobile ? '|m' : '|d' ) ) . '.html';
$rcac_ttl      = 1800; /* RCPC_TTL in the mu-plugin: 30 minutes. */

if ( is_readable( $rcac_file ) && ( time() - filemtime( $rcac_file ) ) < $rcac_ttl ) {
	$rcac_head = @file_get_contents( $rcac_file, false, null, 0, 3 );
	if ( '200' === $rcac_head ) {
		header( 'Content-Type: text/html; charset=UTF-8' );
		header( 'Vary: User-Agent' );   /* cache is keyed per device class; proxies must not mix them */
		header( 'X-UK-Cache: hit' );
		$rcac_fp = fopen( $rcac_file, 'rb' );
		if ( $rcac_fp ) {
			fseek( $rcac_fp, 3 );       /* skip the 3-byte status prefix */
			fpassthru( $rcac_fp );
			fclose( $rcac_fp );
		}
		exit;
	}
}
