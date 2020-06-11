<?php
/*
Plugin Name: IP2Location Tags
Plugin URI: https://ip2location.com/resources/wordpress-ip2location-tag
Description: Enable you to use IP2Location tags to customize your post content by country.
Version: 2.8.3
Author: IP2Location
Author URI: https://www.ip2location.com
*/

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
define('IP2LOCATION_TAGS_ROOT', __DIR__ . DS);

class IP2LocationTags
{
	protected $regions = [];

	function parse_tag($s, $start, $end)
	{
		$s = ' ' . $s;
		$data = strpos($s, $start);

		if ($data == 0) {
			return '';
		}

		$data += strlen($start);
		$len = strpos($s, $end, $data) - $data;

		return substr($s, $data, $len);
	}

	function parse_widget($content)
	{
		// Escape tags
		$content = str_replace(['<', '>'], ['&lt;', '&gt;'], $content);

		// Parse widget content
		$content = $this->parse_content($content);

		// Restore tags and return value
		return str_replace(['&lt;', '&gt;'], ['<', '>'], $content);
	}

	function parse_content($content)
	{
		$ip_address = $_SERVER['REMOTE_ADDR'];

		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		$result = $this->get_location($ip_address);

		if (!$result) {
			$this->write_debug_log('Not able to read IP2Location database.');
			return $content;
		}

		$find = ['{ip:ipAddress}', '{ip:countryCode}', '{ip:countryName}', '{ip:regionName}', '{ip:cityName}', '{ip:latitude}', '{ip:longitude}', '{ip:isp}', '{ip:domainName}', '{ip:zipCode}', '{ip:timeZone}', '{ip:netSpeed}', '{ip:iddCode}', '{ip:areaCode}', '{ip:weatherStationCode}', '{ip:weatherStationName}', '{ip:mcc}', '{ip:mnc}', '{ip:mobileCarrierName}', '{ip:elevation}', '{ip:usageType}', '{ip:countryFlag}', '{ip:countryFlag32}', '{ip:countryFlag64}'];

		$replace = [$result['ipAddress'], $result['countryCode'], $result['countryName'], $result['regionName'], $result['cityName'], $result['latitude'], $result['longitude'], $result['isp'], $result['domainName'], $result['zipCode'], $result['timeZone'], $result['netSpeed'], $result['iddCode'], $result['areaCode'], $result['weatherStationCode'], $result['weatherStationName'], $result['mcc'], $result['mnc'], $result['mobileCarrierName'], $result['elevation'], $result['usageType'], '<img src="' . plugins_url('country-flags/16/' . strtolower($result['countryCode']) . '_16.png', __FILE__) . '" > ', '<img src="' . plugins_url('country-flags/32/' . strtolower($result['countryCode']) . '_32.png', __FILE__) . '" > ', '<img src="' . plugins_url('country-flags/64/' . strtolower($result['countryCode']) . '_64.png', __FILE__) . '" > '];

		// Replace geolocation variables
		$content = str_replace($find, $replace, $content);

		// Replace tags
		$content = $this->replace_content($content, '[', ']', $result);

		// Replace tags (Legacy)
		$content = $this->replace_content($content, '&lt;', '&gt;', $result);

		return $content;
	}

	function replace_content($content, $tag_open, $tag_close, $result)
	{
		// Parse IP2Location tags
		do {
			$to_this = '';

			// Get country list from tag
			$data = $this->parse_tag($content, $tag_open . 'ip:', $tag_close);

			// Get protected text from tag
			$text = $this->parse_tag($content, $tag_open . 'ip:' . $data . $tag_close, $tag_open . '/ip' . $tag_close);

			// Rebuild the whole tag
			$replace_this = $tag_open . 'ip:' . $data . $tag_close . $text . $tag_open . '/ip' . $tag_close;

			$entries = explode(',', str_replace(' ', '', strtoupper($data)));

			// Show content if wildcard defined
			if (in_array('*', $entries)) {
				$to_this = $text;
			}

			// Show content for listed entries
			if (in_array($result['countryCode'], $entries) || in_array($result['countryCode'] . ':' . $this->get_region_code($result['countryCode'], $result['regionName']), $entries)) {
				$to_this = $text;
			}

			// Hide content for prohibited entries
			if (in_array('-' . $result['countryCode'], $entries) || in_array('-' . $result['countryCode'] . ':' . $this->get_region_code($result['countryCode'], $result['regionName']), $entries)) {
				$to_this = '';
			}

			$content = str_replace($replace_this, $to_this, $content);
		} while ($data);

		return $content;
	}

	function admin_options()
	{
		if (!is_admin()) {
			return;
		}

		add_action('wp_enqueue_script', 'load_jquery');

		// Find any .BIN files in current directory
		$files = scandir(IP2LOCATION_TAGS_ROOT);

		foreach ($files as $file) {
			if (strtoupper(substr($file, -4)) == '.BIN') {
				update_option('ip2location_tags_database', $file);
				break;
			}
		}

		$mode_status = '';
		$lookup_mode = (isset($_POST['lookupMode'])) ? $_POST['lookupMode'] : get_option('ip2location_tags_lookup_mode');
		$api_key = (isset($_POST['apiKey'])) ? $_POST['apiKey'] : get_option('ip2location_tags_api_key');
		$enable_debug_log = (isset($_POST['submit']) && isset($_POST['enable_debug_log'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['enable_debug_log']))) ? 0 : get_option('ip2location_tags_debug_log_enabled'));

		if (isset($_POST['lookupMode'])) {
			update_option('ip2location_tags_lookup_mode', $lookup_mode);
			update_option('ip2location_tags_api_key', $api_key);
			update_option('ip2location_tags_debug_log_enabled', $enable_debug_log);

			if ($enable_debug_log) {
				$this->write_debug_log('Debug log enabled.');
			} else {
				$this->write_debug_log('Debug log disabled.');
			}

			$mode_status .= '
			<div id="message" class="updated">
				<p>Changes saved.</p>
			</div>';
		}

		echo '
		<style type="text/css">
			.red{color:#cc0000}
			.code{color:#003399;font-family:\'Courier New\'}
			pre{margin:0 0 20px 0;border:1px solid #c0c0c0;backgroumd:#e4e4e4;color:#535353;font-family:\'Courier New\';padding:8px}
			.result{margin:0 0 20px 0;border:1px solid #006699;backgroumd:#99ffcc;color:#000033;padding:8px}
		</style>

		<script>
			(function( $ ) {
				$(function(){
					$("#download").on("click", function(e){
						e.preventDefault();

						if ($("#productCode").val() == "" || $("#token").val() == ""){
							return;
						}

						$("#download").attr("disabled", "disabled");
						$("#download-status").html(\'<div style="padding:10px; border:1px solid #ccc; background-color:#ffa;">Downloading \' + $("#productCode").val() + \' BIN database in progress... Please wait...</div>\');

						$.post(ajaxurl, { action: "update_ip2location_tags_database", productCode: $("#productCode").val(), token: $("#token").val() }, function(response) {
							if(response == "SUCCESS") {
								alert("Downloading completed.");

								$("#download-status").html(\'<div id="message" class="updated"><p>Successfully downloaded the \' + $("#productCode").val() + \' BIN database. Please refresh information by <a href="javascript:;" id="reload">reloading</a> the page.</p></div>\');

								$("#reload").on("click", function(){
									window.location = window.location.href.split("#")[0];
								});
							}
							else {
								alert("Downloading failed.");

								$("#download-status").html(\'<div id="message" class="error"><p><strong>ERROR</strong>: Failed to download \' + $("#productCode").val() + \' BIN database. Please make sure you correctly enter the product code and login crendential. Please also take note to download the BIN product code only.</p></div>\');
							}
						}).always(function() {
							$("#productCode").val("DB1LITEBIN");
							$("#download").removeAttr("disabled");
						});
					});

					$("#countryCode").on("change", function(){
						$.post(ajaxurl, { "action": "get_region_list", "countryCode": $(this).val() }, function(data){
							$("#regionCode >optgroup").html(data);
						});
					});

					$("#regionCode").on("change", function(){
						$("#region-code").html(\'<div id="message" class="updated"><p>The subdivision code for \' + $("#regionCode option:selected").text() + \', \' + $("#countryCode option:selected").text() + \' will be <strong>\' + $("#countryCode").val() + \':\' + $("#regionCode").val() + \'</strong>.</p></div>\');
					});

					$("#use-bin").on("click", function(){
						$("#bin-mode").show();
						$("#ws-mode").hide();

						$("html, body").animate({
							scrollTop: $("#use-bin").offset().top - 50
						}, 100);
					});

					$("#use-ws").on("click", function(){
						$("#bin-mode").hide();
						$("#ws-mode").show();

						$("html, body").animate({
							scrollTop: $("#use-ws").offset().top - 50
						}, 100);
					});

					$("#' . (($lookup_mode == 'bin') ? 'bin-mode' : 'ws-mode') . '").show();
				});
			})( jQuery );
		</script>

		<div class="wrap">
			<h3>IP2Location Tags</h3>
			<p>
				IP2Location Tags provides a solution to easily get the visitor\'s location information based on IP address and customize the content display for different countries. This plugin uses IP2Location BIN file for location queries, therefore there is no need to set up any relational database to use it. Depending on the BIN file that you are using, this plugin is able to provide you the information of country, region or state, city, latitude and longitude, US ZIP code, time zone, Internet Service Provider (ISP) or company name, domain name, net speed, area code, weather station code, weather station name, mobile country code (MCC), mobile network code (MNC) and carrier brand, elevation and usage type of origin for an IP address.
			</p>

			<p>&nbsp;</p>

			<div style="border-bottom:1px solid #ccc;">
				<h3>Lookup Mode</h3>
			</div>

			' . $mode_status . '

			<form id="form-lookup-mode" method="post">
				<p>
					<label><input id="use-bin" type="radio" name="lookupMode" value="bin"' . (($lookup_mode == 'bin') ? ' checked' : '') . '> Local BIN database</label>

					<div id="bin-mode" style="margin-left:50px;display:none;background:#d7d7d7;padding:20px">';

		if (!is_file(IP2LOCATION_TAGS_ROOT . get_option('ip2location_tags_database'))) {
			echo '
						<div id="message" class="error">
							<p>
								Unable to find the IP2Location BIN database! Please download the database at at <a href="https://www.ip2location.com/?r=wordpress" target="_blank">IP2Location commercial database</a> | <a href="https://lite.ip2location.com/?r=wordpress" target="_blank">IP2Location LITE database (free edition)</a>.
							</p>
						</div>';
		} else {
			if (!class_exists('IP2Location\\Database')) {
				require_once IP2LOCATION_TAGS_ROOT . 'class.IP2Location.php';
			}

			// Create IP2Location object.
			$ipl = new \IP2Location\Database(IP2LOCATION_TAGS_ROOT . get_option('ip2location_tags_database'), \IP2Location\Database::FILE_IO);
			$dbVersion = $ipl->getDatabaseVersion();
			$curdbVersion = str_replace('.', '-', $dbVersion);

			echo '
						<p>
							<b>Current Database Version: </b>
							' . $curdbVersion . '
						</p>';

			if (strtotime($curdbVersion) < strtotime('-2 months')) {
				echo '
							<div style="background:#fff;padding:2px 10px;border-left:3px solid #cc0000">
								<p>
									<strong>REMINDER</strong>: Your IP2Location database was outdated. We strongly recommend you to download the latest version for accurate result.
								</p>
							</div>';
			}
		}

		echo '
						<p>
							BIN file download: <a href="https://www.ip2location.com/?r=wordpress" target="_blank">IP2Location Commercial database</a> | <a href="https://lite.ip2location.com/?r=wordpress" targe="_blank">IP2Location LITE database (free edition)</a>.
						</p>

						<p>&nbsp;</p>

						<div style="border-bottom:1px solid #ccc;">
							<h4>Download & Update IP2Location BIN Database</h4>
						</div>

						<div id="download-status" style="margin:10px 0;"></div>

						<strong>Product Code</strong>:
						<select id="productCode" type="text" value="" style="margin-right:10px;" >
							<option value="DB1LITEBIN">DB1LITEBIN</option>
							<option value="DB3LITEBIN">DB3LITEBIN</option>
							<option value="DB5LITEBIN">DB5LITEBIN</option>
							<option value="DB9LITEBIN">DB9LITEBIN</option>
							<option value="DB11LITEBIN">DB11LITEBIN</option>
							<option value="DB1BIN">DB1BIN</option>
							<option value="DB2BIN">DB2BIN</option>
							<option value="DB3BIN">DB3BIN</option>
							<option value="DB4BIN">DB4BIN</option>
							<option value="DB5BIN">DB5BIN</option>
							<option value="DB6BIN">DB6BIN</option>
							<option value="DB7BIN">DB7BIN</option>
							<option value="DB8BIN">DB8BIN</option>
							<option value="DB9BIN">DB9BIN</option>
							<option value="DB10BIN">DB10BIN</option>
							<option value="DB11BIN">DB11BIN</option>
							<option value="DB1LITEBINIPV6">DB1LITEBINIPV6</option>
							<option value="DB3LITEBINIPV6">DB3LITEBINIPV6</option>
							<option value="DB5LITEBINIPV6">DB5LITEBINIPV6</option>
							<option value="DB9LITEBINIPV6">DB9LITEBINIPV6</option>
							<option value="DB11LITEBINIPV6">DB11LITEBINIPV6</option>
							<option value="DB1BINIPV6">DB1BINIPV6</option>
							<option value="DB2BINIPV6">DB2BINIPV6</option>
							<option value="DB3BINIPV6">DB3BINIPV6</option>
							<option value="DB4BINIPV6">DB4BINIPV6</option>
							<option value="DB5BINIPV6">DB5BINIPV6</option>
							<option value="DB6BINIPV6">DB6BINIPV6</option>
							<option value="DB7BINIPV6">DB7BINIPV6</option>
							<option value="DB8BINIPV6">DB8BINIPV6</option>
							<option value="DB9BINIPV6">DB9BINIPV6</option>
							<option value="DB10BINIPV6">DB10BINIPV6</option>
							<option value="DB11BINIPV6">DB11BINIPV6</option>
						</select>

						<strong>Download Token</strong>:
						<input id="token" type="text" value="' . get_option('ip2location_tags_token') . '" style="margin-right:10px;" />

						<button id="download" class="button action">Download</button>

						<span style="display:block; font-size:0.8em">Get your download token from <a href="https://lite.ip2location.com/file-download" target="_blank">https://lite.ip2location.com/file-download</a> or <a href="https://www.ip2location.com/file-download" target="_blank">https://www.ip2location.com/file-download</a>.</span>

						<div style="margin-top:20px;">
							<strong>Note</strong>: If you failed to download the BIN database using this automated downloading tool, please follow the procedures below to update the BIN database manually.
							<ol style="list-style-type:circle;margin-left:30px">
								<li>Download the BIN database at <a href="https://www.ip2location.com/?r=wordpress" target="_blank">IP2Location commercial database</a> | <a href="https://lite.ip2location.com/?r=wordpress" target="_blank">IP2Location LITE database (free edition)</a>.</li>
								<li>Decompress the zip file and update the BIN database to ' . __DIR__ . '.</li>
								<li>Once completed, please refresh the information by reloading the setting page.</li>
							</ol>
						</div>

						<br><br>
						You may implement automated monthly database update as well. <a href="https://www.ip2location.com/resources/how-to-automate-ip2location-bin-database-download" target="_balnk">Learn more...</a>
						<p>&nbsp;</p>
					</div>
				</p>
				<p>
					<label><input id="use-ws" type="radio" name="lookupMode" value="ws"' . (($lookup_mode == 'ws') ? ' checked' : '') . '> IP2Location Web Service</label>

					<div id="ws-mode" style="margin-left:50px;display:none;background:#d7d7d7;padding:20px">
						<p>Please insert your IP2Location <a href="https://www.ip2location.com/web-service" target="_blank">Web service</a> API key.</p>
						<p>
							<strong>API Key</strong>:
							<input name="apiKey" type="text" value="' . $api_key . '" style="margin-right:10px;" />
						</p>
					</div>
				</p>
				<p>
					<h3>Debugging Logs</h3>
					<label for="enable_debug_log">
						<input type="checkbox" name="enable_debug_log" id="enable_debug_log" value="1"' . (($enable_debug_log == 1) ? ' checked' : '') . ' /> Enable Debug Message Logging
					</label>
				</p>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"  />
				</p>
			</form>

			<p>&nbsp;</p>

			<div style="border-bottom:1px solid #ccc;">
				<h3 id="ip-lookup">Query IP</h3>
			</div>
			<p>
				Enter a valid IP address for checking.
			</p>';

		$ip_address = (isset($_POST['ipAddress'])) ? $_POST['ipAddress'] : '';

		if (isset($_POST['lookup'])) {
			if (!filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
				echo '
					<div id="message" class="error">
						<p><strong>ERROR</strong>: Invalid IP address.</p>
					</div>';
			} else {
				$response = $this->get_location($ip_address);

				if (is_array($response) && $response['countryName']) {
					if ($response['countryCode'] != '??' && strlen($response['countryCode']) == 2) {
						echo '
							<div id="message" class="updated">
								<p>IP address <strong>' . $ip_address . '</strong> belongs to <strong>' . $response['countryName'] . '</strong>.</p>
							</div>';
					} else {
						echo '
							<div id="message" class="error">
								<p><strong>ERROR</strong>: ' . $response['countryName'] . '</p>
							</div>';
					}
				} else {
					echo '
						<div id="message" class="error">
							<p><strong>ERROR</strong>: This IP address is not found in this database.</p>
						</div>';
				}
			}
		}

		echo '
			<form method="post">
				<p>
					<label><b>IP Address: </b></label>
					<input type="text" name="ipAddress" value="' . $ip_address . '" />
					<input type="submit" name="lookup" value="Lookup" class="button action" />
				</p>
			</form>

			<p>&nbsp;</p>

			<div style="border-bottom:1px solid #ccc;">
				<h3>Subdivision Code List</h3>
			</div>
			<p>
				Select a country code for subdivision code list
			</p>';

		if (!is_file(IP2LOCATION_TAGS_ROOT . 'IP2LOCATION-ISO3166-2.CSV')) {
			echo '
			<div id="message" class="error">
				<p>
					IP2Location ISO3166-2 CSV file not found. Please download the CSV file at the following link:
					<a href="https://www.ip2location.com/free/iso3166-2" target="_blank">ISO 3166-2 Subdivision Code</a>.
					<ol class="red" style="list-style-type:circle;margin-left:30px;">
							<li>Download the zip file.</li>
							<li>Decompress the zip file.</li>
							<li>Upload <b>IP2LOCATION-ISO3166-2.CSV</b> to /wp-content/plugins/ip2location-tags/.</li>
							<li>Once completed, please refresh the information by reloading the page.</li>
					</ol>
				</p>
			</div>';
		} else {
			$country_codes = array_keys($this->regions);

			echo '
			<form>
				<select id="countryCode" type="text" size="10" style="height:200px">
					<optgroup label="Choose a Country Code">';

			foreach ($country_codes as $country_code) {
				$country_name = $this->get_country_name($country_code);

				if (!$country_name) {
					continue;
				}

				echo '<option value="' . $country_code . '"> ' . $country_name . '</option>';
			}

			echo '
					</optgroup>
				</select>

				<select id="regionCode" size="10" style="height:200px">
					<optgroup label="Country Subdivision Code List">
					</optgroup>
				</select>

				<div id="region-code"></div>
			</form>';
		}

		echo '
			<p>&nbsp;</p>

			<h3>Get visitor\'s location information with Variable Tag</h3>
			<p>
				<strong>Variable Tag List</strong>
				<ul>
					<li><span class="code">{ip:ipAddress}</span> - Visitor IP address.</li>
					<li><span class="code">{ip:countryCode}</span> - Two-character country code based on ISO 3166.</li>
					<li><span class="code">{ip:countryName}</span> - Country name based on ISO 3166.</li>
					<li><span class="code">{ip:regionName}</span> - Region, province or state name.</li>
					<li><span class="code">{ip:cityName}</span> - City name.</li>
					<li><span class="code">{ip:latitude}</span> - Latitude of the city.</li>
					<li><span class="code">{ip:longitude}</span> - Longitude of the city.</li>
					<li><span class="code">{ip:zipCode}</span> - ZIP/Postal code.</li>
					<li><span class="code">{ip:isp}</span> - Internet Service Provider or company\'s name.</li>
					<li><span class="code">{ip:domainName}</span> - Internet domain name associated to IP address range.</li>
					<li><span class="code">{ip:timeZone}</span> - UTC time zone.</li>
					<li><span class="code">{ip:netSpeed}</span> - Internet connection type. DIAL = dial up, DSL = broadband/cable, COMP = company/T1</li>
					<li><span class="code">{ip:iddCode}</span> - The IDD prefix to call the city from another country.</li>
					<li><span class="code">{ip:areaCode}</span> - A varying length number assigned to geographic areas for call between cities.</li>
					<li><span class="code">{ip:weatherStationCode}</span> - The special code to identify the nearest weather observation station.</li>
					<li><span class="code">{ip:weatherStationName}</span> - The name of the nearest weather observation station.</li>
					<li><span class="code">{ip:mcc}</span> - Mobile Country Codes (MCC) as defined in ITU E.212 for use in identifying mobile stations in wireless telephone networks, particularly GSM and UMTS networks.</li>
					<li><span class="code">{ip:mnc}</span> - Mobile Network Code (MNC) is used in combination with a Mobile Country Code (MCC) to uniquely identify a mobile phone operator or carrier.</li>
					<li><span class="code">{ip:mobileCarrierName}</span> - Commercial brand associated with the mobile carrier.</li>
					<li><span class="code">{ip:elevation}</span> - Average height of city above sea level in meters (m).</li>
					<li><span class="code">{ip:usageType}</span> - Usage type classification of ISP or company.</li>
					<li><span class="code">{ip:countryFlag}</span> - Flag of country with size of 16 x 16.</li>
					<li><span class="code">{ip:countryFlag32}</span> - Flag of country with size of 32 x 32.</li>
					<li><span class="code">{ip:countryFlag64}</span> - Flag of country with size of 64 x 64.</li>
				</ul>
			</p>
			<p>&nbsp;</p>

			<h4>Usage Example</h4>

			<p>
				<b>Display visitor\'s IP address, country name, region name and city name.</b>
				<pre>Your IP is {ip:ipAddress}
You are in {ip:countryName}, {ip:regionName}, {ip:cityName} </pre>
			</p>

			<p>&nbsp;</p>

			<h3>Customize the post content with IP2Location Tag</h3>
			<p>
				<h4>Syntax to show content for specific country</h4>
				<pre>&#91;ip:XX[,XX]..[,XX]&#93;Your content here.&#91;/ip&#93;</pre>
				<div class="red">Note: XX is a two-character ISO-3166 country code.</div>
			</p>
			<p>
				<strong>Example</strong><br/>
				To show the content for United States or Canada visitors only.<br/>
				<pre>&#91;ip:US,CA&#93;Only visitors from United States or Canada can view this line.&#91;/ip&#93;</pre>
			</p>
			<p>&nbsp;</p>
			<p>
				<h4>Syntax to show content for specific country and region</h4>
				<pre>&#91;ip:XX:YY[,XX:YY]..[,XX:YY]&#93;Your content here.&#91;/ip&#93;</pre>
				<div class="red">Note: XX is a two-character ISO-3166 country code and YY is a ISO-3166-2 sub-division code.</div>
			</p>
			<p>
				<strong>Example</strong><br/>
				To show the content for California or New York visitors only.<br/>
				<pre>&#91;ip:US:CA,US:NY&#93;Only visitors from California or New York can view this line.&#91;/ip&#93;</pre>
			</p>
			<p>&nbsp;</p>

			<p>
				<h4>Syntax to hide the content from specific country</h4>
				<pre>&#91;ip:*,-XX[,-XX]..[,-XX]&#93;Your content here.&#91;/ip&#93;</pre>
				<div class="red">Note: XX is a two-character ISO-3166 country code.</div>
			</p>
			<p>
				<strong>Example</strong><br/>
				All visitors will be able to see the line except visitors from Vietnam.</br>
				<pre>&#91;ip:*,-VN&#93;All visitors will be able to see this line except visitors from Vietnam.&#91;/ip&#93;</pre>
			</p>

			<p>&nbsp;</p>

			<p>
				<h4>Syntax to hide the content from specific country and region</h4>
				<pre>&#91;ip:*,-XX:YY[,-XX:YY]..[,-XX:YY]&#93;Your content here.&#91;/ip&#93;</pre>
				<div class="red">Note: XX is a two-character ISO-3166 country code and YY is a ISO-3166-2 sub-division code.</div>
			</p>
			<p>
				<strong>Example</strong><br/>
				All visitors will be able to see the line except visitors from California.</br>
				<pre>&#91;ip:*,-US:CA&#93;All visitors will be able to see this line except visitors from California.&#91;/ip&#93;</pre>
			</p>

			<p>&nbsp;</p>

			<h3>References</h3>

			<p>Please visit <a href="https://www.ip2location.com/free/country-multilingual" target="_blank">https://www.ip2location.com</a> for ISO country codes and names supported.</p>

			<p>&nbsp;</p>

			<p>If you like this plugin, please leave us a <a href="https://wordpress.org/support/view/plugin-reviews/ip2location-tags">5 stars rating</a>. Thank You!</p>
		</div>';
	}

	function admin_page()
	{
		add_options_page('IP2Location Tags', 'IP2Location Tags', 8, 'ip2location-tags', [&$this, 'admin_options']);
	}

	function set_defaults()
	{
		if (get_option('ip2location_tags_lookup_mode') !== false) {
			return;
		}

		update_option('ip2location_tags_lookup_mode', 'bin');
		update_option('ip2location_tags_api_key', '');
		update_option('ip2location_tags_database', '');

		// Find any .BIN files in current directory
		$files = scandir(IP2LOCATION_TAGS_ROOT);

		foreach ($files as $file) {
			if (strtoupper(substr($file, -4)) == '.BIN') {
				update_option('ip2location_tags_database', $file);
				break;
			}
		}
	}

	function init()
	{
		add_action('admin_menu', [&$this, 'admin_page']);
		add_filter('the_content', [&$this, 'parse_content']);
		add_filter('widget_text', [&$this, 'parse_widget']);

		// Get regions
		if (($handle = fopen(IP2LOCATION_TAGS_ROOT . 'IP2LOCATION-ISO3166-2.CSV', 'r')) !== false) {
			while (($data = fgetcsv($handle, 1000, ',')) !== false) {
				if (strpos($data[2], '-') === false) {
					continue;
				}

				list($country_code, $region_code) = explode('-', $data[2]);
				$this->regions[$country_code][$region_code] = $data[1];
			}
			fclose($handle);
		}
	}

	function get_location($ip)
	{
		switch (get_option('ip2location_tags_lookup_mode')) {
			case 'bin':
				$this->write_debug_log('Lookup by BIN database.');
				// Make sure IP2Location database is exist.
				if (!is_file(IP2LOCATION_TAGS_ROOT . get_option('ip2location_tags_database'))) {
					$this->write_debug_log('IP2Location BIN database not found.');
					return false;
				}

				if (!class_exists('IP2Location\\Database')) {
					require_once IP2LOCATION_TAGS_ROOT . 'class.IP2Location.php';
				}

				// Create IP2Location object.
				$geo = new \IP2Location\Database(IP2LOCATION_TAGS_ROOT . get_option('ip2location_tags_database'), \IP2Location\Database::FILE_IO);

				// Get geolocation by IP address.
				$response = $geo->lookup($ip, \IP2Location\Database::ALL);

				$this->write_debug_log('Geolocation result for [' . $ip . '] found: ' . print_r($response, true));

				return [
					'ipAddress'          => $ip,
					'countryCode'        => $response['countryCode'],
					'countryName'        => $response['countryName'],
					'regionName'         => $response['regionName'],
					'cityName'           => $response['cityName'],
					'latitude'           => $response['latitude'],
					'longitude'          => $response['longitude'],
					'isp'                => $response['isp'],
					'domainName'         => $response['domainName'],
					'zipCode'            => $response['zipCode'],
					'timeZone'           => $response['timeZone'],
					'netSpeed'           => $response['netSpeed'],
					'iddCode'            => $response['iddCode'],
					'areaCode'           => $response['areaCode'],
					'weatherStationCode' => $response['weatherStationCode'],
					'weatherStationName' => $response['weatherStationName'],
					'mcc'                => $response['mcc'],
					'mnc'                => $response['mnc'],
					'mobileCarrierName'  => $response['mobileCarrierName'],
					'elevation'          => $response['elevation'],
					'usageType'          => $response['usageType'],
				];
			break;

			case 'ws':
				$this->write_debug_log('Lookup by Web service.');

				if (!class_exists('WP_Http')) {
					include_once ABSPATH . WPINC . '/class-http.php';
				}

				$request = new WP_Http();
				$response = $request->request('https://api.ip2location.com/v2/?' . http_build_query([
					'key'     => get_option('ip2location_tags_api_key'),
					'ip'      => $ip,
					'package' => 'WS10',
					'format'  => 'json',
				]), ['timeout' => 3]);

				if (($json = json_decode($response['body'])) === null) {
					$this->write_debug_log('Geolocation result for [' . $ip . '] found: ' . print_r($json, true));

					return [
						'ipAddress'          => $ip,
						'countryCode'        => $json->country_code,
						'countryName'        => $json->country_name,
						'regionName'         => $json->region_name,
						'cityName'           => $json->city_name,
						'latitude'           => $json->latitude,
						'longitude'          => $json->longitude,
						'isp'                => $json->isp,
						'domainName'         => $json->domain,
						'zipCode'            => $json->zip_code,
						'timeZone'           => '-',
						'netSpeed'           => '-',
						'iddCode'            => '-',
						'areaCode'           => '-',
						'weatherStationCode' => '-',
						'weatherStationName' => '-',
						'mcc'                => '-',
						'mnc'                => '-',
						'mobileCarrierName'  => '-',
						'elevation'          => '-',
						'usageType'          => '-',
					];
				}

				$this->write_debug_log('Web service connection error.');
			break;
		}
	}

	function download()
	{
		set_time_limit(600);

		try {
			$productCode = (isset($_POST['productCode'])) ? $_POST['productCode'] : '';
			$token = (isset($_POST['token'])) ? $_POST['token'] : '';

			if (!class_exists('WP_Http')) {
				include_once ABSPATH . WPINC . '/class-http.php';
			}

			// Remove existing database.zip.
			if (is_file(IP2LOCATION_TAGS_ROOT . 'database.zip')) {
				@unlink(IP2LOCATION_TAGS_ROOT . 'database.zip');
			}

			// Start downloading BIN database from IP2Location website.
			$request = new WP_Http();
			$response = $request->request('https://www.ip2location.com/download?' . http_build_query([
				'file'  => $productCode,
				'token' => $token,
			]), ['timeout' => 120]);

			if ((isset($response->errors)) || (!(in_array('200', $response['response'])))) {
				die('Connection error.');
			}

			// Save downloaded package into plugin directory.
			$fp = fopen(IP2LOCATION_TAGS_ROOT . 'database.zip', 'w');

			fwrite($fp, $response['body']);
			fclose($fp);

			// Decompress the package.
			$zip = zip_open(IP2LOCATION_TAGS_ROOT . 'database.zip');

			if (!is_resource($zip)) {
				die('Downloaded file is corrupted.');
			}

			while ($entries = zip_read($zip)) {
				// Extract the BIN file only.
				$file_name = zip_entry_name($entries);

				if (substr($file_name, -4) != '.BIN') {
					continue;
				}

				// Remove existing BIN files before extrac the latest BIN file.
				$files = scandir(IP2LOCATION_TAGS_ROOT);

				foreach ($files as $file) {
					if (strtoupper(substr($file, -4)) == '.BIN') {
						@unlink(IP2LOCATION_TAGS_ROOT . $file);
					}
				}

				$handle = fopen(IP2LOCATION_TAGS_ROOT . $file_name, 'w+');
				fwrite($handle, zip_entry_read($entries, zip_entry_filesize($entries)));
				fclose($handle);

				if (!is_file(IP2LOCATION_TAGS_ROOT . $file_name)) {
					die('ERROR');
				}

				@unlink(IP2LOCATION_TAGS_ROOT . 'database.zip');

				update_option('ip2location_tags_token', $token);

				die('SUCCESS');
			}
		} catch (Exception $e) {
			die('ERROR');
		}

		die('ERROR');
	}

	function set_case($s)
	{
		return preg_replace("/\w[\w']*/e", "ucwords('\\0')", strtolower($s));
	}

	function region_list()
	{
		$country_code = (isset($_POST['countryCode'])) ? $_POST['countryCode'] : '';

		if (isset($this->regions[$country_code])) {
			sort($this->regions[$country_code]);
			foreach ($this->regions[$country_code] as $region_code => $region_name) {
				echo '<option value="' . str_pad($region_code, 2, '0', STR_PAD_LEFT) . '"> ' . $this->set_case($region_name) . '</option>';
			}
		}
	}

	function get_country_name($code)
	{
		$countries = ['AF' => 'Afghanistan', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Cote D\'Ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'CD' => 'Democratic Republic of Congo', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TP' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands (Malvinas)', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'FX' => 'France, Metropolitan', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GN' => 'Guinea', 'GW' => 'Guinea-bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard and Mc Donald Islands', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran (Islamic Republic of)', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KR' => 'Korea, Republic of', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Lao People\'s Democratic Republic', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libyan Arab Jamahiriya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macau', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia, Federated States of', 'MD' => 'Moldova, Republic of', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'AN' => 'Netherlands Antilles', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'VC' => 'Saint Vincent and the Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovak Republic', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia And The South Sandwich Islands', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SH' => 'St. Helena', 'PM' => 'St. Pierre and Miquelon', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen Islands', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syrian Arab Republic', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania, United Republic of', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UM' => 'United States Minor Outlying Islands', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican City State (Holy See)', 'VE' => 'Venezuela', 'VN' => 'Viet Nam', 'VG' => 'Virgin Islands (British)', 'VI' => 'Virgin Islands (U.S.)', 'WF' => 'Wallis and Futuna Islands', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'YU' => 'Yugoslavia', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe'];

		return (isset($countries[$code])) ? $countries[$code] : '';
	}

	function get_region_code($country_code, $region_name)
	{
		if (!isset($this->regions[$country_code])) {
			return false;
		}

		foreach ($this->regions[$country_code] as $rc => $rn) {
			if (strtoupper($region_name) == $rn) {
				return $rc;
			}
		}

		return false;
	}

	function plugin_admin_notices()
	{
		if (get_user_meta(get_current_user_id(), 'ip2location_tags_admin_notice', true) === 'dismissed') {
			return;
		}

		$url = get_admin_url() . 'options-general.php?page=ip2location-tags';
		$currentscr = get_current_screen();

		if ($currentscr->parent_base == 'plugins') {
			if (is_plugin_active('ip2location-tags/ip2location-tags.php')) {
				echo '
					<div id="ip2location-tags-notice" class="updated notice is-dismissible">
						<h2>IP2Location Tags is almost ready!</h2>
						<p>Download and update IP2Location BIN database for accurate result.</p>
						<p>
							<a href=' . $url . ' class="button button-primary"> Download Now </a>
							<a href="https://www.ip2location.com/?r=wordpress" class="button"> Learn more </a>
						</p>
					</div>
				';
			}
		}
	}

	function plugin_enqueues()
	{
		if (is_admin() && get_user_meta(get_current_user_id(), 'ip2location_tags_admin_notice', true) !== 'dismissed') {
			wp_enqueue_script('ip2location_tags_admin_script', plugins_url('/js/notice-update.js', __FILE__), ['jquery'], '1.0', true);

			wp_localize_script('ip2location_tags_admin_script', 'ip2location_tags_admin', ['ip2location_tags_admin_nonce' => wp_create_nonce('ip2location_tags_admin_nonce')]);
		}
	}

	function plugin_dismiss_admin_notice()
	{
		if (!isset($_POST['ip2location_tags_admin_nonce']) || !wp_verify_nonce($_POST['ip2location_tags_admin_nonce'], 'ip2location_tags_admin_nonce')) {
			wp_die();
		}

		update_user_meta(get_current_user_id(), 'ip2location_tags_admin_notice', 'dismissed');
	}

	public function write_debug_log($message)
	{
		if (!get_option('ip2location_tags_debug_log_enabled')) {
			return;
		}

		file_put_contents(IP2LOCATION_TAGS_ROOT . 'debug.log', gmdate('Y-m-d H:i:s') . "\t" . $message . "\n", FILE_APPEND);
	}
}

$ip2location_tags = new IP2LocationTags();
$ip2location_tags->init();

register_activation_hook(__FILE__, [$ip2location_tags, 'set_defaults']);

add_action('wp_ajax_update_ip2location_tags_database', [$ip2location_tags, 'download']);
add_action('wp_ajax_get_region_list', [$ip2location_tags, 'region_list']);

add_action('admin_enqueue_scripts', [$ip2location_tags, 'plugin_enqueues']);
add_action('admin_notices', [$ip2location_tags, 'plugin_admin_notices']);
add_action('wp_ajax_ip2location_tags_admin_notice', [$ip2location_tags, 'plugin_dismiss_admin_notice']);
