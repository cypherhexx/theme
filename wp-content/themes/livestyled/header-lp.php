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



	<main class="global-main" id="aria-main">