<?php 
/* ========================================================================================================================
    Add defer attribute to script tags
======================================================================================================================== */

function add_defer_attribute($tag, $handle) {
    // add script handles to the array below
    $scripts_to_defer = array('br-scripts-libs','br-scripts-app','gform_gravityforms','gform_json','gform_placeholder');
        
    foreach($scripts_to_defer as $defer_script) {
        if ($defer_script === $handle) {
            return str_replace(' src', ' defer src', $tag);
        }
    }
    return $tag;
}
add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);