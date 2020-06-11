<?php
/**
 * Stanard config
 */
	$sectionAlignment = get_sub_field( 'cib_section_alignment' );
/**
 * Image
 */
    $sectionImage = get_sub_field( 'cib_image' );
    $sectionImageTheme = get_sub_field( 'cib_image_theme' );
/**
 * Video
 */
	$sectionVideo = get_sub_field( 'cib_video' );
/**
 * Copy
 */	
    $sectionHeaderIcon = get_sub_field( 'cib_header_icon' );
	$sectionHeader = get_sub_field( 'cib_main_header' );
	$sectionSubHeader = get_sub_field( 'cib_sub_header' );
    $sectionCopy = get_sub_field( 'cib_copy' );
    $sectionCopyTheme = get_sub_field( 'cib_copy_theme' );
/**
 * CTAs
 */
	$sectionCtas = get_sub_field( 'cib_ctas' );
	$sectionCtaSize = $sectionCtas['cib_cta_size'];
	$sectionCtaRepeater = $sectionCtas['cib_ctas_repeater'];
?>

<article class="section section--copy section--copy-image-boxed section--<?php echo $section; ?>" id="section-<?php echo $section; ?>">

    <div class="grid grid--2 flexbox flex-wrap theme--<?php echo $sectionImageTheme; ?>">
        <?php 
            if( $sectionAlignment === 'left' ):
                include( 'pb-copy-image-boxed--copy.php' );
                include( 'pb-copy-image-boxed--image.php' );
            else:
                include( 'pb-copy-image-boxed--image.php' );
                include( 'pb-copy-image-boxed--copy.php' );
            endif;
        ?>
    </div>
	
</article>
