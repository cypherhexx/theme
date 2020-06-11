<?php
$sectionBlocks = get_sub_field( 'awards' );
$count = count( $sectionBlocks );
if( $count === 3 || $count === 6 || $count == 9 ):
    $grid = 3;
else:
    $grid = 4;
endif;
?>

<article class="section section--<?php echo $section; ?> align--center theme--alternative" id="section-<?php echo $section; ?>">
	
	<section class="container">

        <h2 class="section__header">Meet the Team</h2>
        
        <div class="grid grid--<?php echo $grid; ?> team-members">

            <?php $n=0; while(have_rows('team_members')): the_row(); $n++; ?>

                <div class="block block--margins team-member anim-500 anim-d-<?php echo $n; ?>00" data-animate="fadeInUp">
                    
                    <?php if(get_sub_field('mtt_picture')): ?>
                    <img class="team-member__pic lazyload" data-src="<?php echo get_sub_field('mtt_picture')['sizes']['image-400x400']; ?>" alt="<?php the_sub_field('mtt_name'); ?> profile picture">
                    <?php endif; ?>

                    <p class="team-member__name"><?php the_sub_field('mtt_name'); ?></p>

                    <?php if(get_sub_field('mtt_job_title')): ?>
                    <p class="team-member__job-title"><?php the_sub_field('mtt_job_title'); ?></p>
                    <?php endif; ?>

                </div>

            <?php endwhile; ?>

        </div>

	</section>

</article>

<?php $section++; ?>