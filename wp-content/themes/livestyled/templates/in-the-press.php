<article class="section section--scrollable section--<?php echo $section; ?> align--center theme--secondary" id="section-<?php echo $section; ?>">

    <h3 class="section__header">In The Press</h3>

    <section class="media-blocks-wrap--scrollable flexbox hide-scrollbar scroll-touch drag-scroll">

        <?php $n=0; while(have_rows('press_items')): the_row(); $n++; ?>

        <div class="media-block align--left anim-500 anim-d-<?php echo $n; ?>00" data-animate="fadeInUp">

            <?php if(get_sub_field('press_logo')): ?>

                <img class="media-block__logo lazyload" data-src="<?php echo get_sub_field('press_logo')['sizes']['large']; ?>" alt="<?php the_sub_field('press_company_name'); ?> logo">

            <?php else: ?>

                <h3 class="media-block__title"><?php the_sub_field('press_company_name'); ?></h3>

            <?php endif; ?>

            <?php if(get_sub_field('press_date')): ?>
            <p class="media-block__meta"><?php the_sub_field('press_date'); ?></p>
            <?php endif; ?>

            
            <?php if(get_sub_field('press_text')): ?>
            <div class="media-block__desc">
                <?php the_sub_field('press_text'); ?>
            </div>
            <?php endif; ?>

            <?php if(get_sub_field('press_cta')): ?>
            <a target="_blank" class="cta cta--md cta--pink" href="<?php echo get_sub_field('press_cta')['url'] ?>">Read More</a>
            <?php endif; ?>

        </div>

        <?php endwhile; ?>

	</section>

</article>