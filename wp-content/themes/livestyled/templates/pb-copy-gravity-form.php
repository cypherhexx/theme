<?php
/**
 * Stanard config
 */	
	$sectionAlignment = get_sub_field( 'cgf_section_alignment' );
	$sectionTheme = get_sub_field( 'cgf_theme' );
/**
 * Image
 */
	$sectionImage = get_sub_field( 'cgf_image' );
/**
 * Copy
 */	
	$sectionHeader = get_sub_field( 'cgf_main_header' );
	$sectionSubHeader = get_sub_field( 'cgf_sub_header' );
	$sectionCopy = get_sub_field( 'cgf_copy' );
?>

<article class="section section--copy section--copy-form section--<?php echo $section; ?> theme--<?php echo $sectionTheme; ?> align--center" id="section-<?php echo $section; ?>">
	
	<section class="container container--600 anim-500 anim-d-200" data-animate="fadeInUp">

		<?php
			include( 'pb-copy-form--copy.php' );
			include( 'pb-copy-form--gravity.php' );
		?>
		
	</section>
	
</article>
