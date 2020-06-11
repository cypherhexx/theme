<div class="block block--2-col anim-750" data-animate="fadeInUp-disabled">		
	<?php if( $sectionHeader ) : ?>
	<h1 class="section__header section__header--copy-image"><?php echo $sectionHeader; ?></h1>
	<?php endif; ?>

	<?php if( $sectionSubHeader ) : ?>
	<h2 class="section__sub-header section__sub-header--copy-image"><?php echo $sectionSubHeader; ?></h2>
	<?php endif; ?>

	<div class="section__copy section__copy--copy-image">
		<?php if( $sectionCopy ) : echo $sectionCopy; endif; ?>

		<?php 
			if( $sectionCtaRepeater ) :
				foreach( $sectionCtaRepeater as $cta ):
					$ctaLink = $cta['ci_cta_link'];
					$ctaStyle = $cta['ci_cta_style'];
					?>
				<a class="cta cta--<?php echo $sectionCtaSize;?> cta--<?php echo $ctaStyle; ?>" href="<?php echo $ctaLink['url']; ?>" target="<?php echo esc_attr($ctaLink['target'] ? $ctaLink['target'] : '_self'); ?>"><?php echo $ctaLink['title']; ?></a>
				<?php
				endforeach;
			endif;
		?>
	</div>
</div>