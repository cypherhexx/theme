<div class="block">	
		
	<?php if( $sectionHeader ) : ?>
	<h1 class="section__header"><?php echo $sectionHeader; ?></h1>
	<?php endif; ?>

	<?php if( $sectionSubHeader ) : ?>
	<h2 class="section__sub-header"><?php echo $sectionSubHeader; ?></h2>
	<?php endif; ?>

	<div class="copy-form__description">
		<?php if( $sectionCopy ) : echo $sectionCopy; endif; ?>
	</div>

</div>