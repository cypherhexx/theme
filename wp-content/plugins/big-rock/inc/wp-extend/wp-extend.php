<?php
	/**
	 * Add excerpts to pages
	 */
	add_post_type_support( 'page', 'excerpt' );
	remove_filter( 'the_excerpt', 'wpautop' );
	