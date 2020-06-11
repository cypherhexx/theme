<?php
get_header();
get_template_part( 'templates/global', 'social-icons' );
get_template_part( 'templates/pb-banner-full-bleed' );
?>


<?php
if(is_singular('case-studies')):
    get_template_part( 'templates/global', 'sections' );
else: ?>
    
    <article class="section section--copy" id="section-2">

        <section class="container container--900">
        
            <div class="section__copy section__copy--blog">
                <?php
                if( have_posts() ):
                    while( have_posts() ): the_post();
                        the_content();
                    endwhile;
                endif; ?>
            </div>

        </section>

    </article>

<?php
endif;
?>


<?php if (get_the_author_meta('description')): ?>
<article class="section section--author theme--alternative">
    <section>

        <div class="container container--900 flexbox flex-wrap clearfix">

            <div class="author__img">
                <?php echo get_avatar(get_the_author_meta('user_email'), '270');?>
            </div>

            <div class="author__bio">
                <p>Written by</p>
                <h3 class="author__name">
                    <?php esc_html(the_author_meta('display_name')); ?>
                    <?php
                    $author_id = get_the_author_meta('ID');
                    if(get_field('job_title','user_'.$author_id)): echo "<span class='author__job-title'>".get_field('job_title','user_'.$author_id)."</span>"; endif; ?>
                </h3>
                <p class="author__description"><?php esc_textarea(the_author_meta('description')); ?></p>
            </div>

        </div>

    </section>
</article>
<?php endif; ?>


<?php if(get_field('app_store_link') || get_field('google_play_link')): ?>
<article class="section section--app-download theme--secondary align--center">
    <section class="container">

        <p>Download the app and take a look for yourself.<br> Available on both iOS and Android.</p>

        <div>
            <a target="_blank" href="<?php the_field('app_store_link'); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/app-store.png" alt="Download on the App Store"></a>
            <a target="_blank" href="<?php the_field('google_play_link'); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/google-play.png" alt="Get it on Google Play"></a>
        </div>

    </section>
</article>
<?php endif; ?>


<?php

include( 'templates/pb-request-demo-form.php' );

get_footer();