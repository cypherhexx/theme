=== IP2Location Redirection ===
Contributors: IP2Location
Donate link: http://www.ip2location.com
Tags: ip2location, country redirection, geolocation, targeted content, ip address, 301, 302, country, ipv4, ipv6
Requires at least: 2.0
Tested up to: 5.2.1
Stable tag: 1.15.8

Redirects visitors to a blog page or a predefined URL based on their country geolocated using IP address.

== Description ==

*This plugin will NOT working if any cache plugin is enabled.*

Enables user to easily redirect visitors to a blog page on your site or a predefined URL based on their country geolocated using IP address.

Key Features

* Redirects visitors to a blog page based on their country
* Redirects visitors to a predefined URL based on their country
* Allows you to configure multiple redirection rules as needed
* Supports 301 & 302 redirection
* Supports IPv4 and IPv6

This plugin supports both IP2Location BIN data and web service for geolocation queries. If you are using the BIN data, you can update the BIN data every month by using the wizard on the settings page for the accurate result. Alternatively, you can also manually download and update the BIN data file using the below links:

BIN file download: [IP2Location Commercial database](http://ip2location.com "IP2Location commercial database") | [IP2Location LITE database](http://lite.ip2location.com "IP2Location LITE database")

If you are using the web service, please visit [IP2Location Web Service](http://www.ip2location.com/web-service "IP2Location Web Service") for details.

= More Information =
Please visit us at [http://www.ip2location.com](http://www.ip2location.com "http://www.ip2location.com")

== Frequently Asked Questions ==
= Do I need to download the BIN file after the plugin installation? =
Yes, please download the latest DB1 BIN file for a quick test from https://lite.ip2location.com/database/ip-country

= Where can I download the BIN file? =
You can download the free LITE edition at [http://lite.ip2location.com](http://lite.ip2location.com "http://lite.ip2location.com") or commercial edition at [http://www.ip2location.com](http://www.ip2location.com "http://www.ip2location.com"). Decompress the downloaded .BIN file and upload it to `wp-content/uploads/ip2location`.

= Do I need to update the BIN file? =
We encourage you to update your BIN file every month so that your plugin works with the latest IP geolocation result. The update usually be ready on the 1st week of every calendar month.

= Can I select multiple countries for redirection? =
Yes, you can.

= Does this plugin works with "Cache Plugin", such as W3 Total Cache?
No. You must disable the "Cache Plugin" for our plugin to fucntion correctly.

= How do I test the plugin?
You can use http://www.locabrowser.com to test the result.

= Unable to find your answer here? =
Send us email at support@ip2location.com

== Screenshots ==

1. Redirect visitor from Nigeria to http://google.com.

== Changelog ==
* 1.15.8 Updated IP2Location library to 8.1.0.
* 1.15.7 Fixed a bug when page does not exist.
* 1.15.6 Fixed issue when customized theme is used.
* 1.15.5 Fixed issue with multi-site WordPress.
* 1.15.4 Updated manual upload instructions.
* 1.15.3 Fixed issue download token not saved.
* 1.15.2 Fixed BIN database download issue.
* 1.15.1 Fixed redirection with WooCommerce shopping cart.
* 1.15.0 Moved database file to WordPress upload directory to prevent existing BIN file from deleted.
  Re-structured debugging log and grouped each visitor into same section.
  Improved session cache for faster speed and save Web service queries.
* 1.14.4 No longer look up for BIN file when Web service is used.
* 1.14.3 Updated documentation links.
* 1.14.2 Tested up to WordPress 5.1.1.
* 1.14.1 Fixed IP2Location API check credit interface.
* 1.14.0 Upgraded IP2Location API to v2.
* 1.13.10 Fixed database file detection in both Windows and Linux environment.
* 1.13.9 Prevent redirection when IP2Location database is missing or corrupted.
* 1.13.8 BIN database no longer shipped together to prevent local copy being overwritten.
* 1.13.7 Added LinkedIn and Pinterest into crawler list.
* 1.13.6 Tested with WordPress 5.0.1.
* 1.13.5 Fixed IP detection when server forwarded wrong IP address.
* 1.13.4 Updated country list based on latest ISO-3166 standards.
* 1.13.3 Added page not found handler.
* 1.13.2 Fix bug which prevented rules from being saved.
* 1.13.1 Fix rule insertion bug.
* 1.13.0 Multiple countries redirection is now available with single rule.
* 1.12.0 Domain redirection will now remain the path and query string. Fine tuned rule validations.
* 1.11.2 Minor fixes.
* 1.11.1 Ignore "www." when redirect domain.
* 1.11.0 Added debug log.
* 1.10.2 Minor bugs fixed.
* 1.10.1 Fixed notice dismiss issue.
* 1.10.0 Added domain redirection.
* 1.9.3 Fixed rule validation bugs.
* 1.9.2 Fixed bugs.
* 1.9.1 Minor changes.
* 1.9.0 IP2Location database update changed to use download token.
* 1.8.0 Added option to enable redirection for first time only. Custom URL allowed in "From" page.
* 1.7.6 Prevent duplicated cart items during redirection.
* 1.7.5 Fixed bots detection.
* 1.7.4 Minor changes.
* 1.7.3 Fixed checkbox issues in configuration page.
* 1.7.2 Bug fixes.
* 1.7.1 Minor update.
* 1.7.0 Added exclude option to redirect all countries except a specified country.
* 1.6.0 Added option to stop redirection when bots / crawlers detected. Fixed inifinite loop bug with some pages.
* 1.5.0 Refined GUI and performance improvements.
* 1.4.1 Fixed checkbox issue.
* 1.4.0 Added home page as redirection source.
* 1.3.3 Fixed infinite loop when redirect within same domain using URL mode.
* 1.3.2 Fixed conflicts when multiple IP2Location plugins installed.
* 1.3.1 Added support for custom GET parameter.
* 1.3.0 Use IP2Location PHP 8.0.2 library for lookup.
* 1.2.7 Use latest IP2Location library for lookup.
* 1.2.6 Fixed close sticky information panel issue.
* 1.2.5 Redirections has been disabled on adminsitrator.
* 1.2.4 Fix uninstall function.
* 1.2.3 Prevent settings lost when deactivate/activate the plugin.
* 1.2.2 Use latest IP2Location library and updated the setting page.
* 1.2.1 The redirection source and destination will list out all possible posts & pages now.
* 1.2.0 Multiple country selection added.
* 1.1.15 Tested with WordPress 4.4.
* 1.1.14 Ignore redirection in admin page.
* 1.1.13 Fixed linking issue to database file. Prevent infinite loop if wildcard chosen.
* 1.1.12 Fixed save issues.
* 1.1.11 Fixed warning message in WordPress 4.3.
* 1.1.10 Fixed redirection issues. Fixed errors with earlier version of PHP.
* 1.1.9 Fixed compatible issues with PHP 5.3 or earlier.
* 1.1.8 Fixed errors with PHP 5.3 or earlier.
* 1.1.7 Fixed class name issue when upgrade from previous version.
* 1.1.6 Fixed redirection issue in iOS devices. Use latest IP2Location library.
* 1.1.5 Remain query string after redirected to external URL.
* 1.1.4 Fix redirect issue when URL rewrite is using.
* 1.1.3 Will remain query string in URL after redirection.
* 1.1.0 Added supports for IP2Location Web Service.
* 1.0.1 Fixed issue on activation.
* 1.0.0 First public release.


== Installation ==
### Using WordPress Dashboard
1. Select **Plugins -> Add New**.
1. Search for "IP2Location Redirection".
1. Click on *Install Now* to install the plugin.
1. Click on *Activate* button to activate the plugin.
1. Download IP2Location database from http://lite.ip2location.com (Free) or http://www.ip2location.com (Commercial).
1. Decompress the .BIN file and upload to `wp-content/plugins/ip2location-redirection`.
1. If you have IP2Location Web service purchased at http://www.ip2location.com/web-service, insert your API key in the Settings tab.
1. You can now start using IP2Location Redirection to block visitors.

### Manual Installation
1. Upload the plugin to `/wp-content/plugins/ip2location-redirection` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Download IP2Location database from http://lite.ip2location.com (Free) or http://www.ip2location.com (Commercial).
1. Decompress the .BIN file and upload to `wp-content/plugins/ip2location-redirection`.
1. If you have IP2Location Web service purchased at http://www.ip2location.com/web-service, insert your API key in the Settings tab.
1. You can now start using IP2Location Redirection to redirect visitors.

Please take note that this plugin requires minimum **PHP version 5.4**.