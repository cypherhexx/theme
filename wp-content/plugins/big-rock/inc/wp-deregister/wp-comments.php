<?php
	/**
	 * Disable Pingbacks
	 */
	function bigrock_remove_x_pingback($headers) {
		unset($headers['X-Pingback']);
		return $headers;
	}
	add_filter( 'wp_headers', 'bigrock_remove_x_pingback' );
