<?php

// Remove active state from "Blog" in nav on case study pages
// ---------------------------------------------------------------------------------
function wpdev_nav_classes( $classes, $item ) {
    if( ( is_post_type_archive( 'case-studies' ) || is_singular( 'case-studies' ) || is_tax('case-study-category') )
        && ($item->title == 'Blog' || $item->title == 'Learn') ){
        $classes = array_diff( $classes, array( 'menu-item--active' ) );
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'wpdev_nav_classes', 10, 2 );


// Add active state to "Case Studies" in nav on case study pages
// ---------------------------------------------------------------------------------
function additional_active_item_classes( $classes = array(), $menu_item = false ) {
    // custom taxonomy
    if ( $menu_item->title == 'Case Studies' && is_tax('case-study-category') ) {
        $classes[] = 'menu-item--active';
    }
    // custom post type single
    if ( $menu_item->title == 'Case Studies' && is_singular('case-studies') ) {
        $classes[] = 'menu-item--active';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'additional_active_item_classes', 10, 2 );