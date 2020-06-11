<?php
	$videoProvider = get_sub_field( 'fwv_provider' );
	$videoId = get_sub_field( 'fwv_video_id' );
?>

<article class="section section--video section--<?php echo $section; ?> theme--secondary anim-500" data-animate="fadeInUp-disabled" id="section-<?php echo $section; ?>">
	
	<section>

		<?php if( $videoProvider === 'youtube' ): ?>
		<div class="video-container">
			<iframe loading="lazy" width="1200" height="675" class="lazyload" data-src="https://www.youtube.com/embed/<?php echo $videoId; ?>?controls=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
		<?php elseif( $videoProvider === 'vimeo' ): ?>
			<div class="video-container">
				<iframe loading="lazy" width="1200" height="675" class="lazyload" data-src="https://player.vimeo.com/video/<?php echo $videoId; ?>?title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
			</div>
			<!-- <script src="https://player.vimeo.com/api/player.js"></script> -->
		<?php endif; ?>

	</section>

</article>


