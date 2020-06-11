<?php
/**
 * Stanard config
 */	
	$sectionAlignment = get_sub_field( 'gm_section_alignment' );
	$sectionTheme = get_sub_field( 'gm_theme' );
/**
 * Copy
 */	
	$sectionHeader = get_sub_field( 'gm_main_header' );
	$sectionSubHeader = get_sub_field( 'gm_sub_header' );
	$sectionCopy = get_sub_field( 'gm_copy' );

	$google_maps_api_key = false;
	if( $sectionMap ):
		$map_lat = $sectionMap['lat'];
		$map_lng = $sectionMap['lng'];
	endif;

?>

<article class="section section--copy section--copy-only section--<?php echo $section; ?> align--<?php echo $sectionAlignment; ?> theme--<?php echo $sectionTheme;?>" id="section-<?php echo $section; ?>">
	
<?php if( $sectionImagesActive ): ?>
	<section class="overlay">
<?php else: ?>
	<section>
<?php endif; ?>
		
		<?php if( $sectionHeader ) : ?>
		<h1 class="banner__header"><?php echo $sectionHeader; ?></h1>
		<?php endif; ?>

		<?php if( $sectionSubHeader ) : ?>
		<h2 class="banner__sub-header"><?php echo $sectionSubHeader; ?></h2>
		<?php endif; ?>

		<?php if( $sectionCopy ) :  echo $sectionCopy;  endif; ?>

		<?php if( $google_maps_api_key === true ): ?>
		
		<div class="marker" data-lat="<?php echo $map_lat; ?>" data-lng="<?php echo $map_lng; ?>"></div>

		<?php else: ?>

		<p style="background: red;color: white; padding:10px;"><b>ATTENTION:</b> A Google API Key is required for the site, once obtained developer needs to edit templates/pb-google-maps.php $google_maps_api_key</p>	

		<div class="mapouter">
			<div class="gmap_canvas">
				<iframe loading="lazy" width="100%" height="400" id="gmap_canvas" src="https://maps.google.com/maps?q=Big%20Rock%20London&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
			</div>
		</div>		

		<?php endif; ?>

	</section>

</article>

