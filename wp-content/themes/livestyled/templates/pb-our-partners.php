<?php
	
	$sectionPartners = get_field( 'partners', 'option' );
	$count = count( $sectionPartners );
	if( $count === 4 || $count === 8 ):
		$grid = 4;
	elseif( $count === 3 || $count === 6 ):
		$grid = 3;
	else:
		$grid = 2;
	endif;
?>

<article class="section section--our-partners section--<?php echo $section; ?> align--center" id="section-<?php echo $section; ?>">
	
	<section class="container relative">

		<h3 class="section__header section__header--our-partners">Our Integration Partners</h3>

		<section class="our-clients__logos grid flexbox align-items-center drag-scroll hide-scrollbar scroll-touch anim-500 carousel" data-animate="fadeInUp-disabled">

			<?php while( has_sub_field('partners', 'option') ): ?>

				<?php $image = get_sub_field('logo'); ?>

				<div class="our-clients__logo-block block flexbox align-items-center">

					<img class="rwd lazyload grayscale our-clients__logo" data-src="<?php echo $image['url']; ?>" alt="<?php the_sub_field('name', 'option') ?>" />

				 </div>

			<?php endwhile; ?>

		</section>

		<button class="carousel__arrow carousel__arrow--left"><?php icon('angle-left'); ?></button>
		<button class="carousel__arrow carousel__arrow--right"><?php icon('angle-right'); ?></button>

	</section>

</article>