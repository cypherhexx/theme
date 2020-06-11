<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'ip2location_redirection_enabled' );
delete_option( 'ip2location_redirection_lookup_mode' );
delete_option( 'ip2location_redirection_api_key', '' );
delete_option( 'ip2location_redirection_database' );
delete_option( 'ip2location_redirection_rules' );
delete_option( 'ip2location_redirection_noredirect_enabled' );

wp_cache_flush();
