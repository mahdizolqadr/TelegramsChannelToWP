<?php

if (
	! defined( 'WP_UNINSTALL_PLUGIN' )
||
	! WP_UNINSTALL_PLUGIN
||
	dirname( WP_UNINSTALL_PLUGIN ) != dirname( plugin_basename( __FILE__ ) )
) {
	if ( ! headers_sent() ) {
		if ( function_exists( 'status_header' ) ) {
			status_header( 404 );
		} else if ( function_exists( 'http_response_code' ) ) {
			http_response_code( 404 );
		} else {
			header( 'HTTP/1.1 404 Not Found', true, 404 );
		}
	}
	exit;
}

// site options
if ( function_exists( 'delete_option' ) ) {
	// Delete all admin options
	$__options = array(
		'telegram_widgets',
		'telegram_site_attribution',
		'telegram_share',
	);
	array_walk( $__options, 'delete_option' );
} 
