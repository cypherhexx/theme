
<li class="post--blog block p0 flexbox">

    <a href="<?php the_permalink(); ?>" class="post--blog__img-wrap">
        <?php
        if (has_post_thumbnail()):
            $imgURL = get_the_post_thumbnail_url(get_the_ID(), 'image-16x9');
        else:
            $imgURL = get_stylesheet_directory_uri()."/assets/img/post-img-fallback.jpg";
        endif;
        ?>
        <img class="post--blog__img lazyload" data-src="<?php echo $imgURL; ?>" alt="<?php the_title(); ?>" />
    </a>

    <div class="post--blog__copy flexbox">
        <h3 class="post--blog__title">
            <a class="post--blog__title-link" href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>
        <p class="post--blog__date"><?php the_time('M j, Y'); ?></p>
        <p class="post--blog__desc">
            <?php echo excerpt('25'); ?>
        </p>
        <a class="cta cta--pink cta--md post--blog__cta" href="<?php the_permalink(); ?>">Read more</a>
    </div>

</li>
