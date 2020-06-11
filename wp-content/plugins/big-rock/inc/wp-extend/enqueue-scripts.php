<?php
	/**
	 * Remove jQuery Migrate
	 */
	function bigrock_remove_jquery_migrate($scripts) {
  	if (!is_admin() && isset($scripts->registered['jquery'])) {
      $script = $scripts->registered['jquery'];
      if ($script->deps) {
        $script->deps = array_diff($script->deps, array(
          'jquery-migrate'
        ));
      }
    }
	}
	add_action( 'wp_default_scripts', 'bigrock_remove_jquery_migrate' );


/**
 *
 * Load JS
 *
 */
	function bigrock_enqueue_scripts() {
		wp_register_script( 'br-scripts-libs', get_template_directory_uri() . '/assets/js/libs/app-libs-min.js','','',true);
		wp_enqueue_script( 'br-scripts-libs' );

		wp_register_script( 'br-scripts-app', get_template_directory_uri() . '/assets/js/app-min.js','','',true);
		wp_enqueue_script( 'br-scripts-app' );
	}
	add_action( 'wp_enqueue_scripts', 'bigrock_enqueue_scripts' );