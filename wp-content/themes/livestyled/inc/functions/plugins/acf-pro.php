<?php
/**
 * Hide ACF on stag and prod
 */
	function br_acf_hide_acf_admin() {
		$site_url = get_bloginfo( 'url' );
		$protected_urls = array(
			'https://www.livestyled.com',
			'https://livestyled.staging.wearebigrock.com'
		);
		if ( in_array( $site_url, $protected_urls ) ) {
			return false;
		} else {
			return true;	
		}
	}
	add_filter( 'acf/settings/show_admin', 'br_acf_hide_acf_admin' );
