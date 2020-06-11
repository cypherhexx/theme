
<article class="section section--copy section--copy-blocks section--<?php echo $section; ?> theme--secondary align--center copy-blocks--mob-side-by-side" id="section-<?php echo $section; ?>">
	
	<section class="container">
		
		<h2 class="section__header">Latest Posts</h2>

		<section class="grid grid--3 grid--mob-side-by-side hide-scrollbar align--left">
        
            <?php
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 3,
            );
            $arr_posts = new WP_Query( $args );
            
            if ($arr_posts->have_posts()): ?>
                <ul class="flexbox w100">
                <?php $n=0; while ($arr_posts->have_posts()): $n++;
                    $arr_posts->the_post();
                        include('post-blog.php');
                    endwhile; ?>
                </ul>
            <?php endif; ?>
            <?php wp_reset_query(); ?>

		</section>

	</section>

</article>
