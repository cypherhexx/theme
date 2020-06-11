<?php
	/**
	 * Removed admin bar
	 */
	show_admin_bar( false );
	/**
	 * Remove widgets
	 */
	function bigrock_remove_dashboard_widgets() {
    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
  }
  add_action( 'wp_dashboard_setup', 'bigrock_remove_dashboard_widgets' );

  
  /**
   * Remove the content editor from ALL pages 
   */
  function bigrock_remove_content_editor() { 
    remove_post_type_support( 'page', 'editor' );
  }
  add_action('admin_head', 'bigrock_remove_content_editor');
