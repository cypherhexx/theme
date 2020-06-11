<?php 
/* ========================================================================================================================
    Cleanup CSS and JS included by Gravity Forms
======================================================================================================================== */

// Remove the FOUR (!!) Gravity Forms Stylesheets
// (this is terrible for page speed and we can write CSS ourselves)
add_filter('pre_option_rg_gforms_disable_css', '__return_true');


// Wrap the inline jQuery that Gravity Forms includes in an Event Listener so
// we can load jQuery footer and ensure the inline block runs after it
add_filter("gform_init_scripts_footer", "init_scripts");
function init_scripts() {
return true;
}
add_filter( 'gform_cdata_open', 'wrap_gform_cdata_open' );
function wrap_gform_cdata_open( $content = '' ) {
	$content = 'document.addEventListener( "DOMContentLoaded", function() { ';
	return $content;
}
add_filter( 'gform_cdata_close', 'wrap_gform_cdata_close' );
function wrap_gform_cdata_close( $content = '' ) {
	$content = ' }, false );';
	return $content;
}

// Remove the jQuery loaded by Gravity Forms (this unfortunately seems the only way to do it)
wp_deregister_script( 'jquery' );
wp_register_script( 'jquery', '', '', '', true );