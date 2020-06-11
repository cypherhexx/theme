<?php
	if (class_exists('IP2LocationTags')) {
			global $ip2location_tags;
			$location = $ip2location_tags->get_location('IP_ADDRESS');
	}

  //$location = array("countryCode"=>"US");
  //echo "<p>test".ip2loc_get_country_code()."</p>";
?>

<?php // if( (get_bloginfo('name') == "Realife Tech" && ip2loc_get_country_code() == "US") || (get_bloginfo('name') == "Realife Tech US" && ip2loc_get_country_code() != "US") ): ?>

  <div class="country-selector flexbox justify-content-center">

    <p class="txt">Choose another country to see content specific to your location</p>

    <?php if(/*get_bloginfo('name') == "Realife Tech" &&*/ ip2loc_get_country_code() == "US"): ?>

      <select name="" id="">
        <option class="us" value="<?php echo get_site_url(); ?>/us/?noredirect=true">United States</option>
        <option class="uk" value="<?php echo get_site_url(); ?>/?noredirect=true">United Kindom</option>
      </select>

    <?php elseif(/*get_bloginfo('name') == "Realife Tech US" &&*/ ip2loc_get_country_code() != "US"): ?>

      <select name="" id="">
        <option class="uk" value="<?php echo get_site_url(); ?>/?noredirect=true">United Kingdom</option>
        <option class="us" value="<?php echo get_site_url(); ?>/us/?noredirect=true">United States</option>
      </select>

    <?php endif; ?>

    <button class="cta cta--pink cta--sm continue">Continue</button>

    <button class="country-selector__close">
      <?php icon('times'); ?>
    </button>

  </div>

<?php // endif; ?>