<?php

	$gdpr = get_field( 'gdpr', 'option' );
	$gdprHeader = $gdpr['header'];
	$gdprAcceptText = $gdpr['accept_cta'];
	$gdprDeclinetext = $gdpr['decline_cta'];
	$gdprContent = $gdpr['intro_copy'];

?>
<article class="section section--gdpr flexbox">
		
	<section class="section--gdpr-outer flexbox justify-content-center">
	
		<div class="section--gdpr-inner flexbox flex-wrap align-items-center justify-content-center">
				
			<?php if( $gdprHeader ): ?>
			<h1 class="gdpr__header"><?php echo $gdprHeader; ?></h1>
			<?php endif;?>

			<?php if( $gdprContent ): echo $gdprContent; endif;?>

			<?php if( $gdprAcceptText ): ?>
			<button class="cta cta--md btn btn--accept gtm--accept" type="button" aria-label="Accept cookie Ploicy, close dialog" title="Accept and close">&#10003; <?php echo $gdprAcceptText; ?></button>
			<?php endif;?>

			<div class="gdpr__small-links">
				<a href="<?php echo get_page_link(256); ?>">Learn More</a>

				<?php if( $gdprDeclinetext ): ?>
				<button class="btn btn--decline gtm--decline" type="button" aria-label="Decline cookie Ploicy, close dialog" title="Decline and close">&#10005; <?php echo $gdprDeclinetext; ?></button>
				<?php endif;?>
			</div>

		</div>

	</section>

</article>