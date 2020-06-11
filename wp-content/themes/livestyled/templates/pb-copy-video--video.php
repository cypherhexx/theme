<div class="block">
	<?php if( $videoProvider === 'youtube' ): ?>
		<div class="video-container">
			<iframe loading="lazy" width="1200" height="675"  src="https://www.youtube.com/embed/<?php echo $videoId; ?>?controls=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		<?php elseif( $videoProvider === 'vimeo' ): ?>
			<div class="video-container">
				<iframe loading="lazy" width="1200" height="675" src="https://player.vimeo.com/video/<?php echo $videoId; ?>?title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
			</div>
			<!-- <script src="https://player.vimeo.com/api/player.js"></script> -->
		<?php endif; ?>
</div>
