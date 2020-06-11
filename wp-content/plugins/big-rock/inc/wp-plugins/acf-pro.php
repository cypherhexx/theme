<?php
	/**
	 * ACF LOCAL JSON
	 */
	add_filter( 'acf/settings/save_json', 'bigrock_acf_json_sync' );
	function bigrock_acf_json_sync( $path ) {
		$path = get_stylesheet_directory() . '/acf-json';
		return $path;
	}

	/**
	 * ACF OPTION PAGES
	 */
	if ( function_exists( 'acf_add_options_page' ) ) {
		$parent = acf_add_options_page( array(
				'menu_title' => 'Site Config',
				'redirect' => false
		));
		acf_add_options_sub_page( array(
				'page_title' => 'Social & Contacts',
				'menu_title' => 'Social & Contacts',
				'parent_slug' => $parent['menu_slug'],
		));

	}
