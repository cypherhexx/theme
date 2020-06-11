<?php
	/**
		*
		* Plugin Name: Livestyled
		* Description: Custom plugin containing all custom post types and taxonomies required for the site.
		* Version: 1.0.0
		* Author: Big Rock
		* Author URI: https://www.wearebigrock.com/
	*/
	/// Extends
	include( plugin_dir_path(__FILE__) . 'inc/wp-extend/wp-menus.php' );
	
	
	
	include( plugin_dir_path(__FILE__) . 'inc/custom/tracking-gtm.php' );
	include( plugin_dir_path(__FILE__) . 'inc/custom/display-svg-icon.php' );
	include( plugin_dir_path(__FILE__) . 'inc/custom/allow-svg-upload.php' );
	include( plugin_dir_path(__FILE__) . 'inc/custom/gravity-forms-cleanup.php' );
	include( plugin_dir_path(__FILE__) . 'inc/custom/defer-scripts.php' );
	include( plugin_dir_path(__FILE__) . 'inc/custom/remove-p-tag-from-images.php' );
	include( plugin_dir_path(__FILE__) . 'inc/custom/limit-content-length.php' );
	include( plugin_dir_path(__FILE__) . 'inc/custom/blog-ajax.php' );
	include( plugin_dir_path(__FILE__) . 'inc/custom/cpt-nav-active-states.php' );
	include( plugin_dir_path(__FILE__) . 'inc/custom/ip2-location-get-country-code.php' );


	
	// Remove main stylesheet so it can be loaded asynchronously via loadCSS
	function my_deregister_styles() {
		wp_deregister_style('br-styles');
	}
	add_action('wp_print_styles', 'my_deregister_styles', 100);

	
	
	/// Custom Post Types
	include( plugin_dir_path(__FILE__) . 'inc/custom-post-types/_case-studies.php' );


	/// Custom Taxonomies
	 include( plugin_dir_path(__FILE__) . 'inc/custom-post-taxonomies/_case-study-categories.php' );


	/**
	 * Extending plugins
	 */
	include( plugin_dir_path(__FILE__) . 'inc/plugins/acf-pro.php' );
	