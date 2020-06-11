<?php
/**
 * Stanard config
 */	
	$sectionAlignment = get_sub_field( 'co_section_alignment' );
	$sectionTheme = get_sub_field( 'co_theme' );
/**
 * Image setup
 */
	if( get_sub_field( 'co_background_images' ) ):
		$sectionImages = get_sub_field( 'co_background_images' );
		if( $sectionImages['co_landscape'] ):
			$sectionImagesActive = true;
			$sectionLandscapeUrl = $sectionImages['co_landscape']['sizes']['image-max-16x9'];
			$sectionPortraitUrl = $sectionImages['co_portrait']['sizes']['image-max-9x16'];
		endif; 
	else:
		$bannerImagesActive = false;
	endif; 
/**
 * Copy
 */	
	$sectionHeader = get_sub_field( 'co_main_header' );
	$sectionSubHeader = get_sub_field( 'co_sub_header' );
	$sectionCopy = get_sub_field( 'co_copy' );
/**
 * CTAs
 */
	$sectionCtas = get_sub_field( 'co_ctas' );
	$sectionCtaSize = $sectionCtas['co_cta_size'];
	$sectionCtaRepeater = $sectionCtas['co_ctas_repeater'];
?>


<?php if( $sectionImagesActive ): ?>
<style>
	.section--<?php echo $section;?> {
		background: url( '<?php echo $sectionLandscapeUrl;?>' ) no-repeat center center;
	}
	
	@media screen and (orientation: portrait) {
		.section--<?php echo $section;?> {
			background: url( '<?php echo $sectionPortraitUrl;?>' ) no-repeat center top;
		}
	}

	@media screen and (orientation: landscape) {
		.section--<?php echo $section;?> {
			background: url( '<?php echo $sectionLandscapeUrl;?>' ) no-repeat center center;
		}
	}

	@media screen and (min-width: 768px) (orientation: portrait) {
		.section--<?php echo $section;?> {
			background: url( '<?php echo $sectionPortraitUrl;?>' ) no-repeat center top;
		}
	}

	@media screen and (min-width: 769px) {
		.section--<?php echo $section;?> {
			background: url( '<?php echo $sectionLandscapeUrl;?>' ) no-repeat center center;
		}
	}

	.section--<?php echo $section;?> {
		background-size: cover;
	}
</style>
<?php endif; ?>

<article class="section section--copy section--copy-only section--<?php echo $section; ?> align--<?php echo $sectionAlignment; ?> theme--<?php echo $sectionTheme;?>" id="section-<?php echo $section; ?>">
	
<?php if( $sectionImagesActive ): ?>
	<section class="overlay">
<?php else: ?>
<section class="container <?php if ( $sectionAlignment == 'header-left' ): echo "grid grid--2"; else: echo "container--900"; endif; ?>">
<?php endif; ?>

		<?php if ( $sectionAlignment == 'header-left' ): ?>
		<div class="block block--2-col">
		<?php endif; ?>

			<?php if( $sectionHeader ) : ?>
			<h1 class="section__header section__header--copy-only anim-500" data-animate="fadeInUp-disabled"><?php echo $sectionHeader; ?></h1>
			<?php endif; ?>

			<?php if( $sectionSubHeader ) : ?>
			<h2 class="section__sub-header section__sub-header--copy-only"><?php echo $sectionSubHeader; ?></h2>
			<?php endif; ?>

		<?php if ( $sectionAlignment == 'header-left' ): ?>
		</div>
		<?php endif; ?>


		<div class="<?php if ( $sectionAlignment == 'header-left' ): echo "block block--2-col"; endif; ?> section__copy anim-500 anim-d-300" data-animate="fadeInUp-disabled">

			<?php if( $sectionCopy ) :  echo $sectionCopy;  endif; ?>

			<?php 
				if( $sectionCtaRepeater ) :
					foreach( $sectionCtaRepeater as $cta ):
						$ctaText = $cta['co_cta_text'];
						$ctaLink = $cta['co_cta_link'];
						$ctaStyle = $cta['co_cta_style'];
						?>
						<a class="cta cta--<?php echo $sectionCtaSize;?> cta--<?php echo $ctaStyle; ?>" href="<?php echo $ctaLink['url']; ?>" target="<?php echo esc_attr($ctaLink['target'] ? $ctaLink['target'] : '_self'); ?>"><?php echo $ctaLink['title']; ?></a>
					<?php
					endforeach;
				endif;
			?>

		</div>

	</section>

</article>

<?php $sectionImagesActive = false; ?>
