<article class="section section--tabbed section--<?php echo $section; ?> align--center theme--secondary" id="section-<?php echo $section; ?>">
	
	<section class="container container--1100 p0">

		<section class="tabs pb-tabbed">


			<?php // Tabs
			// ----------------------------------------- ?>
			<ul class="tabs-nav pb-tabbed__nav flexbox justify-content-space-between hide-scrollbar scroll-touch">
            <?php $n=0; while( has_sub_field('tc_tabs') ): $n++ ?>
            
                <li class="pb-tabbed__nav-item flexbox align-items-center justify-content-center anim-500 anim-d-<?php echo $n; ?>00" data-animate="fadeInUp">

				    <?php if(get_sub_field('tc_tab_icon')): ?>
                    <img class="rwd lazyload pb-tabbed__icon" data-src="<?php echo get_sub_field('tc_tab_icon')['url']; ?>" alt="<?php the_sub_field('tc_tab_label') ?> icon" />
                    <?php endif; ?>

                    <span class="pb-tabbed__label">
                        <?php the_sub_field('tc_tab_label') ?>
                    </span>
                    
				</li>

			<?php endwhile; ?>
			</ul>


			<?php // Content Panels
			// ----------------------------------------- ?>
			<div class="pb-tabbed__panels">
			<?php while( has_sub_field('tc_tabs') ): ?>
                
                <div class="tabs-panel pb-tabbed__panel">
                    <div class="pb-tabbed__panel-content">
                        <?php the_sub_field('tc_tab_content'); ?>
                    </div>
                </div>
            
			<?php endwhile; ?>
			</div>


		</section>

	</section>

</article>