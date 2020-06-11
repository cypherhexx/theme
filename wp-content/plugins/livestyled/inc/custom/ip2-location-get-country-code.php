<?php

if ( !function_exists( 'ip2loc_get_country_code' ) ) {
	function ip2loc_get_country_code() {
			global $ip2location_tags;

			if ( !class_exists('IP2LocationTags') )
					return '';

			$ip_address = $_SERVER['REMOTE_ADDR'];

			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
					$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}

			$location = $ip2location_tags->get_location($ip_address);
			if ( !$location )
					return '';

			return $location['countryCode'];
	}
}

?>