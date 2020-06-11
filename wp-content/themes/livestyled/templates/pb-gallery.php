<?php
/**
 * Stanard config
 */	
	$sectionAlignment = get_sub_field( 'g_alignment' );
	$sectionTheme = get_sub_field('g_theme');

/**
 * Copy
 */	
	$sectionHeader = get_sub_field( 'g_main_header' );
	$sectionSubHeader = get_sub_field( 'g_sub_header' );
	$sectionCopy = get_sub_field( 'g_copy' );
?>

<article class="section section--gallery section--<?php echo $section; ?> theme--<?php echo $sectionTheme;?> align--<?php echo $sectionAlignment; ?>" id="section-<?php echo $section; ?>">
	
	<section class="container">
		
		<?php if( $sectionHeader ) : ?>
		<h1 class="section__header"><?php echo $sectionHeader; ?></h1>
		<?php endif; ?>

		<?php if( $sectionSubHeader ) : ?>
		<h2 class="section__sub-header"><?php echo $sectionSubHeader; ?></h2>
		<?php endif; ?>

		<?php if( $sectionCopy ) :  echo $sectionCopy;  endif; ?>

		<?php
			$images = get_sub_field('g_gallery');
			if( $images ): ?>
			<ul class="gallery grid grid--5 flexbox flex-wrap justify-content-center">
				<?php $n=0; foreach( $images as $image ): $n++; ?>
					<li class="gallery__item block flexbox justify-content-center">
						<img class="gallery__img lazyload rwd anim-500 anim-d-<?php echo $n; ?>00" data-animate="fadeInUp" data-src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>" />
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

	</section>

</article>


