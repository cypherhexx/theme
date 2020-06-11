<?php 
/* ========================================================================================================================
	Icon Function (show icon from your icons SVG sprite)
======================================================================================================================== */

function icon($iconName) {
	echo '<svg class="icon icon-'.$iconName.'"><use xlink:href="'.get_stylesheet_directory_uri().'/assets/img/icons-sprite.svg#icon-'.$iconName.'"></use></svg>';
}