<section class="block clearfix">
	<?php
		$gravityform = get_sub_field( 'cgf_form' );
		$gravityformName = $gravityform['title'];
		$tabindex = 3;
		gravity_form( $gravityformName, $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = true, $tabindex, $echo = true );
	?>
</section>