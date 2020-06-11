<article class="section section--copy section--copy-form section--<?php echo $section; ?> theme--primary align--center" id="section-<?php echo $section; ?>">
	
	<section class="container container--600 anim-500" data-animate="fadeInUp-disabled">

		<?php
			$form = GFAPI::get_form('1'); // ID for Request a demo form
		?>

		<div class="block">
			<h1 class="section__header"><?php echo $form['title']; ?></h1>

			<div class="copy-form__description">
				<?php echo $form['description']; ?>
			</div>
		</div>

		<section class="block clearfix">
			<?php
				gravity_form( $form['title'], $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = true, $tabindex = 3, $echo = true );
			?>
		</section>
		
	</section>
	
</article>