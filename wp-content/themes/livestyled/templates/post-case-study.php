
<li class="post--case-study block p0 flexbox anim-500" data-animate="fadeInUp">

    <a href="<?php the_permalink(); ?>" class="post--case-study__img-wrap">
        <?php
        if (has_post_thumbnail()):
            $imgURL = get_the_post_thumbnail_url(get_the_ID(), 'image-16x9');
        else:
            $imgURL = get_stylesheet_directory_uri()."/assets/img/post-img-fallback.jpg";
        endif;
        ?>
        <img class="post--case-study__img lazyload" data-src="<?php echo $imgURL; ?>" alt="<?php the_title(); ?>" />
    </a>

    <div class="post--case-study__copy flexbox">
        <h3 class="post--case-study__title">
            <a class="post--case-study__title-link" href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>
        <p class="post--case-study__cat"><?php the_category(' '); ?></p>
        <p class="post--case-study__desc">
            <?php echo excerpt('25'); ?>
        </p>
        <a class="cta cta--pink cta--md post--case-study__cta" href="<?php the_permalink(); ?>">View case study</a>
    </div>

</li>
