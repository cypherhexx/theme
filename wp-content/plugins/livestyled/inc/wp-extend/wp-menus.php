<?php
	/**
	 * Custom menus
	 */
	function bigrock_register_menus() {
		register_nav_menus(
			array(
				'main' => __( 'Main Menu' ),
				'legal' => __( 'Footer Menu' )
			)
		);
	}
	add_action( 'after_setup_theme', 'bigrock_register_menus' );
