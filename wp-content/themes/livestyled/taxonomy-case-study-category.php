<?php
get_header();
get_template_part( 'templates/global', 'social-icons' );
get_template_part( 'templates/pb-banner-full-bleed' );
?>

<article class="section theme--secondary" id="section-2">

    <section class="container">

        <h2 class="section__header section__header--normal-case"><?php single_term_title(); ?></h2>

        <ul class="post-list post-list--sectioned flexbox flex-wrap">
            <?php while( have_posts() ): the_post();
                get_template_part( 'templates/post-case-study' );
            endwhile; ?>
        </ul>

        <a href="<?php echo get_post_type_archive_link('case-studies'); ?>">Back to all case studies</a>

    </section>
	

	<section class="container flexbox justify-content-center">
		<?php if(function_exists('wp_paginate')) {
			wp_paginate();
		} ?>
	</section>

</article>


<?php
include( 'templates/pb-request-demo-form.php' );

get_footer();