<?php
	/**
	 * Remove Query Strings
	 */
	function bigrock_remove_cssjs_ver( $src ) {
		$src = remove_query_arg( array( 'v', 'ver', 'rev' ), $src );
		return $src;
	}
	add_filter( 'style_loader_src', 'bigrock_remove_cssjs_ver', 10, 2 );
	add_filter( 'script_loader_src', 'bigrock_remove_cssjs_ver', 10, 2 );
	add_filter( 'link_loader_href', 'bigrock_remove_cssjs_ver', 10, 2 );

	/**
	 * Removes a number of feeds from the head
	 */
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
	remove_action( 'wp_head', 'rel_canonical' );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'feed_links', 2 );

	/**
	 * Remove Emoji
	 */
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

	/**
	 * Remove WordPress generator
	 */
	function bigrock_generator_filter ( $html, $type ) {
		if ( $type == 'xhtml' ) {
			$html = preg_replace( '("WordPress.*?")', '"Big Rock"', $html );
		}
		return $html;
	}
	add_filter( 'the_generator', 'bigrock_generator_filter', 10, 2 );

