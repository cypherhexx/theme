<?php
/*
	Template Name: Privacy Policy
*/
get_header('lp');
if( have_posts() ):
	while( have_posts() ): the_post();
		get_template_part( 'templates/global', 'sections' );
	endwhile;
endif;
get_footer('lp');
