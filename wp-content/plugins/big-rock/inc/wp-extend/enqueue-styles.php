<?php
	function bigrock_enqueue_style() {
	  wp_enqueue_style( 'br-styles', get_template_directory_uri() . '/assets/css/style.css' );
	}
	add_action( 'wp_enqueue_scripts', 'bigrock_enqueue_style' );
	