<?php
	/**
	 * Add headers
	 */
	function bigrock_add_headers() {
		header( "X-Frame-Options: sameorigin" );
		header( "X-XSS-Protection: 1; mode=block" );
		header( "X-Content-Type-Options: nosniff" );
		header( "Strict-Transport-Security: max-age=31536000" );
		header( "Referrer-Policy: origin-when-cross-origin" );
		header( "Expect-CT: max-age=7776000, enforce" );
	}
	add_action( 'send_headers', 'bigrock_add_headers' );

	/**
	 * Author Page redirect
	 */
	function bigrock_author_page_redirect() {
		if ( is_author() ) {
			wp_redirect( home_url() );
		}
	}
	add_action( 'template_redirect', 'bigrock_author_page_redirect' );

	/**
 	* Author URL redirect
 	*/
	function bigrock_author_request( $query_vars ) {
		if ( array_key_exists( 'author_name', $query_vars ) ) {
			global $wpdb;
			$author_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='nickname' AND meta_value = %s", $query_vars['author_name'] ) );
			if ( $author_id ) {
				$query_vars['author'] = $author_id;
				unset( $query_vars['author_name'] );
			}
		}
		return $query_vars;
	}
	add_filter( 'request', 'bigrock_author_request' );

	function bigrock_author_link( $link, $author_id, $author_nicename ){
		$author_nickname = get_user_meta( $author_id, 'nickname', true );
		if ( $author_nickname ) {
				$link = str_replace( $author_nicename, $author_nickname, $link );
		}
		return $link;
	}
	add_filter( 'author_link', 'bigrock_author_link', 10, 3 );

	function bigrock_set_user_nicename_to_nickname( &$errors, $update, &$user ){
		if ( ! empty( $user->nickname ) ) {
				$user->user_nicename = sanitize_title( $user->nickname, $user->display_name );
		}
	}
	add_action( 'user_profile_update_errors', 'bigrock_set_user_nicename_to_nickname', 10, 3 );
