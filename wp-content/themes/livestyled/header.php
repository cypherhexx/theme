<?php
if(isset($_GET["noredirect"])) {
	setcookie ( 'lang', "yes", time() + 60*60*24*30, '/');
}
if(is_404() && isset($_GET["noredirect"])) {
	header( "Location: ".get_site_url() );
}
?>

<!DOCTYPE html>
<html lang="en" class="no-js" dir="ltr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<link rel="stylesheet" href="https://use.typekit.net/ocg4wmb.css">

	<style type="text/css">
	<?php include('assets/css/critical.css'); ?>
	.mob-nav,.section--gdpr,.scroll-arrow{display:none;}
	</style>

	<?php /* Load CSS async */ ?>
  	<script>
		function loadCSS(e,t,n){"use strict";function o(){var t;for(var i=0;i<s.length;i++){if(s[i].href&&s[i].href.indexOf(e)>-1){t=true}}if(t){r.media=n||"all"}else{setTimeout(o)}}var r=window.document.createElement("link");var i=t||window.document.getElementsByTagName("script")[0];var s=window.document.styleSheets;r.rel="stylesheet";r.href=e;r.media="only x";i.parentNode.insertBefore(r,i);o();return r}

		loadCSS( "<?php echo get_theme_file_uri('assets/css/style.css'); ?>" );
	</script>

  	<?php /* No JS support */ ?>
	<noscript>
		<link rel="stylesheet" href="<?php echo get_theme_file_uri('/css/style.css'); ?>">
	</noscript>

	<?php wp_head(); ?>
</head>
<body>

	<a class="srt" href="#aria-main">Skip to main content</a>

	<?php
		if( !is_page(  array( 'privacy', 'privacy-policy', 'cookie-privacy' ) ) ) :
			get_template_part( 'templates/global-gdpr' );
		endif;
		//get_template_part( 'templates/global-header' );
	?>

	<?php
	if($_COOKIE['lang'] != "yes" && !isset($_GET["noredirect"])) {
		get_template_part('templates/country-selector');
	}
	?>

	<header class="header flexbox align-items-center">
			<a class="site-logo" href="<?php echo get_site_url(); ?>">
				<img class="site-logo__img" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo.svg" alt="<?php echo get_bloginfo(); ?>">
			</a>

		<nav class="desktop-nav">
			<?php
			// Primary menu for desktop
				wp_nav_menu([
					'menu' => 'Main Menu',
					'menu_class' => "main-menu",
					'container' => ''
				]);
			?>
		</nav>

		<a class="cta cta--ghost cta--white cta--ghost cta--sm header__contact-cta" href="<?php echo get_page_link(195); ?>">Contact Us</a>

		<button class="mob-nav-toggle">
			<?php icon('bars'); ?>
		</button>

		<nav class="mob-nav">
			<div class="scroll-container"><?php /* Main menu will be cloned into here to form the mobile nav */ ?></div>
		</nav>
	</header>

	<main class="global-main" id="aria-main">