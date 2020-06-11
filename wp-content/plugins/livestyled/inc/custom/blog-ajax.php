<?php

// Category Filters
function my_filters(){
    wp_reset_query();
    
    $args = array(
        'orderby' => 'date', 
        'order' => $_POST['date'] 
    );
  
    if( isset( $_POST['categoryfilter'] ) ) :

        if($_POST['categoryfilter'] == 'all') {
            $args['tax_query'] = array(
                array(
                    'posts_per_page' => -1,
                    'nopaging' => true
                )
            );
        } else {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'id',
                    'posts_per_page' => -1,
                    'nopaging' => true,
                    'terms' => $_POST['categoryfilter']
                )
            );
        }
    endif;
   
    $query = new WP_Query( $args );
  
    if( $query->have_posts() ) :
        while( $query->have_posts() ): $query->the_post();
        include get_stylesheet_directory() . '/templates/post-blog.php';
        endwhile;
        wp_reset_postdata();
    else :
        echo 'No posts found';
    endif;
  
    die();
}
  
add_action('wp_ajax_customfilter', 'my_filters'); 
add_action('wp_ajax_nopriv_customfilter', 'my_filters');




// Load More Posts
function loadmore_ajax_handler(){
 
	$args = json_decode( stripslashes( $_POST['query'] ), true );
	$args['paged'] = $_POST['page'] + 1;
	$args['post_status'] = 'publish';
 
	query_posts( $args );
 
	if( have_posts() ) :
		while( have_posts() ): the_post();
            include get_stylesheet_directory() . '/templates/post-blog.php';
		endwhile;
  endif;
	die;
}
 
add_action('wp_ajax_loadmore', 'loadmore_ajax_handler');
add_action('wp_ajax_nopriv_loadmore', 'loadmore_ajax_handler');