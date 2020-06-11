<?php
/*
Template Name: About
*/

get_header();
get_template_part( 'templates/global', 'social-icons' );

// Hero section via Page Builder
get_template_part( 'templates/global', 'sections' );


// Content Sections
$section=1; while(have_rows('content_sections')): the_row(); $section++; ?>

    <article class="section section--copy section--copy-only section--about-content section--<?php echo $section; ?> align--header-left <?php if($section % 2 == 0){ echo "theme--secondary"; } else { echo "theme--alternative"; } ?>" id="section-<?php echo $section; ?>">

        <section class="container container--1150 grid grid--2">

            <div class="block block--2-col">
                <h1 class="section__header section__header--copy-only section__header--normal-case"><?php the_sub_field('cs_heading'); ?></h1>

                <?php if(get_sub_field('cs_image_1')): ?>
                <img class="about-content__img about-content__img--1 lazyload" data-src="<?php echo get_sub_field('cs_image_1')['sizes']['large']; ?>" alt="<?php the_sub_field('cs_heading'); ?> 1">
                <?php endif; ?>
            </div>

            <div class="block block--2-col section__copy">
                <?php the_sub_field('cs_content'); ?>

                <?php if(get_sub_field('cs_image_2')): ?>
                <img class="about-content__img about-content__img--2 lazyload" data-src="<?php echo get_sub_field('cs_image_2')['sizes']['large']; ?>" alt="<?php the_sub_field('cs_heading'); ?> 2">
                <?php endif; ?>
            </div>

        </section>

    </article>

<?php endwhile;

//include( 'templates/meet-the-team.php' );

include( 'templates/awards.php' );

include( 'templates/our-offices.php' );

include( 'templates/in-the-press.php' );

include( 'templates/pb-request-demo-form.php' );

get_footer();