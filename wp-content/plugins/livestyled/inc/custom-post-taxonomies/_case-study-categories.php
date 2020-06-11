<?php

	function br_tax_case_study_categories() {

		$plural = 'Case Study Categories';
		$single = 'Case Study Category';
		$slug = 'case-study-category';

		$labels = array(
			'name' =>  $plural,
			'singular_name' =>  $single,
			'menu_name' =>  $plural,
			'all_items' => 'All' . $plural,
			'parent_item' => 'Parent ' . $single,
			'parent_item_colon' => 'Parent ' . $single . ':',
			'new_item_name' => 'New ' . $single,
			'add_new_item' => 'Add New ' . $single,
			'edit_item' => 'Edit ' . $single,
			'update_item' => 'Update ' . $single,
			'view_item' => 'View ' . $single,
			'separate_items_with_commas' => 'Separate ' . $plural . ' with commas',
			'add_or_remove_items' => 'Add or remove ' . $single,
			'choose_from_most_used' => 'Choose from the most used',
			'popular_items' => 'Popular ' . $single,
			'search_items' => 'Search ' . $single,
			'not_found' => 'Not found',
			'no_terms' => 'No' . $single,
			'items_list' => $single . ' list',
			'items_list_navigation' => $single . ' list navigation',
		);

		$args = array(
			'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'show_admin_column' => true,
      'update_count_callback' => '_update_post_term_count',
      'query_var' => true,
      'rewrite' => array( 'slug' => 'case-study-categories' ),
		);
		register_taxonomy( $slug, array( 'case-studies' ), $args );

		add_filter('manage_edit-' . $slug . '_columns', function ( $columns ) {
			if( isset( $columns['description'] ) )
				unset( $columns['description'] );
			return $columns;
		});

	}

	add_action( 'init', 'br_tax_case_study_categories', 0 );
