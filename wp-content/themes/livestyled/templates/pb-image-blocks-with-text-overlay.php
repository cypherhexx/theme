<?php
// Image Blocks With Text Overlay
/**
 * Stanard config
 */	
	$sectionTheme = get_sub_field('ibwto_theme');

/**
 * Copy
 */	
	$sectionHeader = get_sub_field( 'ibwto_section_header' );
	$sectionSubHeader = get_sub_field( 'ibwto_section_sub_header' );
?>

<article class="section section--image-blocks section--<?php echo $section; ?> theme--<?php echo $sectionTheme;?> align--center" id="section-<?php echo $section; ?>">
	
	<section class="container">
		
		<?php if( $sectionHeader ) : ?>
		<h1 class="section__header"><?php echo $sectionHeader; ?></h1>
		<?php endif; ?>

		<?php if( $sectionSubHeader ) : ?>
		<h2 class="section__sub-header"><?php echo $sectionSubHeader; ?></h2>
		<?php endif; ?>

		<?php
		if( have_rows('ibwto_image_blocks') ): ?>
			<ul class="ibwto grid grid--5 flexbox flex-wrap justify-content-center">
			<?php $n=0; while( have_rows('ibwto_image_blocks') ): the_row(); $n++; ?>
				<li class="ibwto__item block flexbox justify-content-center">
					<?php $image = get_sub_field('ibwto_image'); ?>
					<img class="ibwto__img lazyload rwd anim-500 anim-d-<?php echo $n; ?>00" data-animate="fadeInUp" data-src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>" />
					<div class="ibwto__text-overlay">
						<h3><?php the_sub_field('ibwto_header'); ?></h3>
						<p><?php the_sub_field('ibwto_copy'); ?></p>
					</div>
				</li>
			<?php endwhile; ?>
			</ul>
		<?php endif; ?>

	</section>

</article>


