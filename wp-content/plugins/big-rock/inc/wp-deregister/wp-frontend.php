<?php
	/**
	 * Disable gutenberg
	 */
	function bigrock_remove_gutenberg_styles() {
		wp_dequeue_style( 'wp-block-library' );
	}
	add_action( 'wp_enqueue_scripts', 'bigrock_remove_gutenberg_styles', 100 );

	/**
	 * Disable wp embed
	 */
	function bigrock_deregister_wp_embed(){
		wp_deregister_script( 'wp-embed' );
	}
	add_action( 'wp_footer', 'bigrock_deregister_wp_embed' );

	/**
	 * Remove s.w.org prefetch
	 */
	remove_action( 'wp_head', 'wp_resource_hints', 2 );

	/**
	 * Removing excessive menu classes and ids
	 */
	function bigrock_css_attributes_filter($var) {
  	return is_array($var) ? array_intersect($var, array( 'current-menu-item' )) : '';
	}
	add_filter('nav_menu_item_id', 'bigrock_css_attributes_filter', 100, 1);

	function bigrock_normalize_wp_classes(array $classes, $item = null){

		$replacements = array(
			'current-menu-item',
			'current-menu-parent',
			'current-menu-ancestor',
			'current_page_item',
			'current_page_parent',
			'current_page_ancestor',
		);

		return array_intersect($replacements, $classes) ? array('menu-item--active') : array();
	}

	add_filter( 'nav_menu_css_class', 'bigrock_normalize_wp_classes', 10, 2 );
	add_filter( 'page_css_class', 'bigrock_normalize_wp_classes', 10, 2 );