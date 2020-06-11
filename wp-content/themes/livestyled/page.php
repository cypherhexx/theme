<?php
	get_header();
	get_template_part( 'templates/global', 'social-icons' );
	if( have_posts() ):
		while( have_posts() ): the_post();
			get_template_part( 'templates/global', 'sections' );
		endwhile;
	endif;
	get_footer();