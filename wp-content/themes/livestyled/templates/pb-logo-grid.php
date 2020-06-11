<?php
/**
 * Stanard config
 */	
	$sectionTheme = get_sub_field( 'lg_theme' );
/**
 * Copy
 */	
	$sectionHeader = get_sub_field( 'lg_main_header' );
	$sectionSubHeader = get_sub_field( 'lg_sub_header' );
	$sectionCopy = get_sub_field( 'lg_copy' );
?>


<article class="section section--copy section--logo-grid section--<?php echo $section; ?> align--center theme--<?php echo $sectionTheme;?>" id="section-<?php echo $section; ?>">
	
  <section class="container">

    <?php if( $sectionHeader ) : ?>
    <h1 class="section__header section__header--logo-grid anim-500" data-animate="fadeInUp-disabled"><?php echo $sectionHeader; ?></h1>
    <?php endif; ?>

    <?php if( $sectionSubHeader ) : ?>
    <h2 class="section__sub-header section__sub-header--logo-grid"><?php echo $sectionSubHeader; ?></h2>
    <?php endif; ?>

		<div class="section__copy anim-500 anim-d-300" data-animate="fadeInUp-disabled">
			<?php if( $sectionCopy ) :  echo $sectionCopy;  endif; ?>
    </div>
    
    <?php if(have_rows('lg_logos')): ?>
    <ul class="logo-grid flexbox flex-wrap">
      <?php while(have_rows('lg_logos')): the_row(); ?>
      <li class="logo-grid__li flexbox align-items-center justify-content-center">
        <?php if(get_sub_field('lg_logo_link')) { echo "<a target='_blank' class='logo-grid__a' href='".get_sub_field('lg_logo_link')['url']."'>"; } ?>
          <img class="lazyload logo-grid__img" data-src="<?php echo get_sub_field('lg_logo')['sizes']['medium']; ?>" alt="<?php echo get_sub_field('lg_logo_name'); ?>">
        <?php if(get_sub_field('lg_logo_link')) { echo "</a>"; } ?>
      </li>
      <?php endwhile; ?>
    </ul>
    <?php endif; ?>

	</section>

</article>

<?php $sectionImagesActive = false; ?>
