<?php
/**
 * Stanard config
 */	
	$sectionAlignment = get_sub_field( 'cv_section_alignment' );
	$sectionTheme = get_sub_field( 'cv_theme' );
	$videoProvider = get_sub_field( 'cv_provider' );
	$videoId = get_sub_field( 'cv_video_id' );
/**
 * Copy
 */	
	$sectionHeader = get_sub_field( 'cv_main_header' );
	$sectionSubHeader = get_sub_field( 'cv_sub_header' );
	$sectionCopy = get_sub_field( 'cv_copy' );
/**
 * CTAs
 */
	$sectionCta = get_sub_field( 'cv_ctas' );
	$sectionCtaSize = $sectionCtas['cv_cta_size'];
	$sectionCtaRepeater = $sectionCtas['cv_ctas_repeater'];
?>

<article class="section section--copy section--copy-video section--<?php echo $section; ?> theme--<?php echo $sectionTheme; ?>" id="section-<?php echo $section; ?>">
	
	<section class="container">

		<div class="grid grid--2">
		<?php 
			if( $sectionAlignment === 'left' ):
				include( 'pb-copy-video--copy.php' );
				include( 'pb-copy-video--video.php' );
			else:
				include( 'pb-copy-video--video.php' );	
				include( 'pb-copy-video--copy.php' );
			endif;
		?>
		</div>
		
	</section>

</article>


