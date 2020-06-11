<article class="section section--<?php echo $section; ?> align--center theme--alternative" id="section-<?php echo $section; ?>">
	
	<section class="container">

        <h2 class="section__header">Our Offices</h2>
        
        <div class="grid grid--2 offices">

            <?php $n=0; while(have_rows('off_offices')): the_row(); $n++; ?>

                <div class="block block--margins copy-block--mob-stacked office anim-500 anim-d-<?php echo $n; ?>00" data-animate="fadeInUp"">
                    
                    <?php if(get_sub_field('off_google_map_embed_code')): ?>
                    <div class="office__map-wrap">
                        <?php echo str_replace(" src", " class='lazyload' data-src", get_sub_field('off_google_map_embed_code')); ?>
                    </div>
                    <?php endif; ?>

                    <?php if(get_sub_field('off_city')): ?>
                    <p class="office__city"><?php the_sub_field('off_city'); ?></p>
                    <?php endif; ?>

                    <?php if(get_sub_field('off_address')): ?>
                    <p class="office__address"><?php the_sub_field('off_address'); ?></p>
                    <?php endif; ?>

                </div>

            <?php endwhile; ?>

        </div>

	</section>

</article>

<?php $section++; ?>