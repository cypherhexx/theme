<?php
	/**
		*
		* Plugin Name: Big Rock
		* Description: Extends WordPress by enhancing the security and speed of the website.
		* Version: 1.0.0
		* Author: Big Rock
		* Author URI: https://www.wearebigrock.com/
	*/
	/// Deregister WordPress Core Settings
	include( plugin_dir_path(__FILE__) . 'inc/wp-deregister/wp-comments.php' );
	include( plugin_dir_path(__FILE__) . 'inc/wp-deregister/wp-frontend.php' );
	include( plugin_dir_path(__FILE__) . 'inc/wp-deregister/wp-dashboard.php' );

	/// Extend WordPress Core Settings
	include( plugin_dir_path(__FILE__) . 'inc/wp-extend/wp-extend.php' );
	include( plugin_dir_path(__FILE__) . 'inc/wp-extend/enqueue-styles.php' );
	include( plugin_dir_path(__FILE__) . 'inc/wp-extend/enqueue-scripts.php' );
	include( plugin_dir_path(__FILE__) . 'inc/wp-extend/dashboard-branding.php' );

	// Extend basic plugins
	include( plugin_dir_path(__FILE__) . 'inc/wp-plugins/acf-pro.php' );
	include( plugin_dir_path(__FILE__) . 'inc/wp-plugins/the-seo-framework.php' );
	/// Custom Post Types


	/// Custom Taxonomies

	/// Security
	include( plugin_dir_path(__FILE__) . 'inc/wp-security/wp-frontend-head.php' );
	include( plugin_dir_path(__FILE__) . 'inc/wp-security/wp-server.php' );
