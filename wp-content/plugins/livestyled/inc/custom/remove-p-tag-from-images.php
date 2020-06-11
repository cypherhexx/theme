<?php 
/* ========================================================================================================================
    Remove <p> tag from images (for blog posts imported from old Wix site)
======================================================================================================================== */

function filter_ptags_on_images($content){
    return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
 }
 
 add_filter('the_content', 'filter_ptags_on_images');