<?php
$sectionBlocks = get_sub_field( 'awards' );
$count = count( $sectionBlocks );
if( $count === 4 || $count === 8 ):
    $grid = 4;
elseif( $count === 2 ):
    $grid = 2;
else:
    $grid = 3;
endif;
?>

<article class="section section--awards section--<?php echo $section; ?> align--center" id="section-<?php echo $section; ?>">
	
	<section class="container">

        <h2 class="section__header">Awards</h2>
        
        <div class="grid grid--<?php echo $grid; ?> awards">

            <?php $n=0; while(have_rows('awards')): the_row(); $n++; ?>

                <div class="block block--margins copy-block--mob-stacked award-block anim-500 anim-d-<?php echo $n; ?>00" data-animate="fadeInUp">
                    
                    <?php if(get_sub_field('awards_event_logo')): ?>
                    <img class="award-block__logo lazyload" data-src="<?php echo get_sub_field('awards_event_logo')['sizes']['large']; ?>" alt="<?php the_sub_field('awards_event_name'); ?> logo">
                    <?php endif; ?>

                    <?php if(get_sub_field('awards_event_name')): ?>
                    <p class="award-block__event-name"><?php the_sub_field('awards_event_name'); ?></p>
                    <?php endif; ?>

                    <?php if(get_sub_field('awards_award_name')): ?>
                    <p class="award-block__award-name"><?php the_sub_field('awards_award_name'); ?></p>
                    <?php endif; ?>

                    <?php if(get_sub_field('awards_year')): ?>
                    <p class="award-block__year"><?php the_sub_field('awards_year'); ?></p>
                    <?php endif; ?>

                </div>

            <?php endwhile; ?>

        </div>

	</section>

</article>

<?php $section++; ?>