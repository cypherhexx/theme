<div class="block">
	<?php if( $sectionHeader ) : ?>
		<h1 class="banner__header"><?php echo $sectionHeader; ?></h1>
		<?php endif; ?>


		<?php if( $sectionSubHeader ) : ?>
		<h2 class="banner__sub-header"><?php echo $sectionSubHeader; ?></h2>
		<?php endif; ?>

		<?php if( $sectionCopy ) : echo $sectionCopy; endif; ?>

		<?php 
			if( $sectionCtaRepeater ) :
				foreach( $sectionCtaRepeater as $cta ):
					$ctaLink = $cta['cv_cta_link'];
					?>
				<a class="cta cta--<?php echo $sectionCtaSize;?> cta--<?php echo $ctaStyle; ?>" href="<?php echo $ctaLink['url']; ?>" target="<?php echo esc_attr($ctaLink['target'] ? $ctaLink['target'] : '_self'); ?>"><?php echo $ctaLink['title']; ?></a>
				<?php
				endforeach;
			endif;
		?>

</div>
