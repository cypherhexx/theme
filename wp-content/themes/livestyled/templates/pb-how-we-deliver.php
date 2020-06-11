<?php
/**
 * Copy
 */	
	$sectionHeader = get_field( 'hwd_section_header', 'option' );
/**
 * Section Blocks
*/
	$sectionBlocks = get_field( 'hwd_blocks', 'option' );
	$count = count( $sectionBlocks );
	if( $count === 5 ):
		$grid = 5;
	elseif( $count === 4 || $count === 8 ):
		$grid = 4;
	elseif( $count === 3 || $count === 6 ):
		$grid = 3;
	else:
		$grid = 2;
	endif;
?>

<article class="section section--copy section--copy-blocks section--<?php echo $section; ?> theme--primary align--center copy-blocks--mob-stacked" id="section-<?php echo $section; ?>">
	
	<section class="container">
		
		<?php if( $sectionHeader ) : ?>
		<h1 class="section__header"><?php echo $sectionHeader; ?></h1>
		<?php endif; ?>

		<section class="grid grid--<?php echo $grid; ?> grid--mob-<?php echo $sectionMobLayout; ?> justify-content-center hide-scrollbar">
		<?php
			$n=0;
			foreach( $sectionBlocks as $block ) :
				$blockImageArray = $block['hwd_block_image'];
				$blockImageUrl = $blockImageArray['sizes']['large'];
				$blockHeader = $block['hwd_block_header'];
				$blockCopy = $block['hwd_block_copy'];
				$n++;
		?>
				<article class="block align--center copy-block copy-block--hwd flexbox align-items-center no-hover anim-2000 anim-d-<?php echo ($n*9)-4; ?>00" data-animate="fadeIn">

					<?php if($blockImageUrl): ?>
					<div class="copy-block__img-wrap copy-block__img-wrap--hwd flexbox align-items-center justify-content-center">
						<img class="rwd lazyload" data-src="<?php echo $blockImageUrl;?>" alt="">
					</div>
					<?php endif; ?>
						
					<?php if( $blockHeader ) : ?>
					<h3 class="copy-block__title copy-block__title--hwd"><?php echo $blockHeader; ?></h3>
					<?php endif; ?>

					<?php if( $blockCopy ): echo $blockCopy; endif; ?>

				</article>

		<?php endforeach; ?>
		</section>

	</section>

</article>
