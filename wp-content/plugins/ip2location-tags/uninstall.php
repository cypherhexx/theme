<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'ip2location_tags_lookup_mode' );
delete_option( 'ip2location_tags_api_key' );
delete_option( 'ip2location_tags_database' );

wp_cache_flush();
