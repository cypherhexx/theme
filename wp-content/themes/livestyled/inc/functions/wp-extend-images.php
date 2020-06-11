<?php 
/**
 * Adding Image support for cropping various sized images
 */
	if ( function_exists( 'add_theme_support' ) ) {
		add_theme_support( 'post-thumbnails' );
/** DO NOT REMOVE ANY OF THE FOLLOWING IMAGE CROPS **/	
		add_image_size( 'thumbnail-16x9', '178', '100',  array( 'center', 'center' ) );
		add_image_size( 'image-lg-16x9', '1600', '900', array( 'center', 'center' ) );
		add_image_size( 'image-max-16x9', '3200', '1800', array( 'center', 'center' ) );
		
		add_image_size( 'image-lg-9x16', '900', '1600', array( 'center', 'center' ) );
		add_image_size( 'image-max-9x16', '1800', '3200', array( 'center', 'center' ) );
		add_image_size( 'thumbnail-9x16', '56', '100',  array( 'center', 'center' ) );
/** ADD ADDITIONAL IMAGE CROPS BELOW HERE */
		add_image_size( 'image-16x9', '600', '388', array( 'center', 'center' ) );	
		add_image_size( 'image-9x16', '388', '600', array( 'center', 'center' ) );	
		add_image_size( 'image-400x400', '400', '400', array( 'center', 'center' ) );	

	}
