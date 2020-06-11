<?php
/**
 * Plugin Name: IP2Location Redirection
 * Plugin URI: https://ip2location.com/resources/wordpress-ip2location-redirection
 * Description: Redirect visitors by their country.
 * Version: 1.15.8
 * Author: IP2Location
 * Author URI: https://www.ip2location.com.
 */
$upload_dir = wp_upload_dir();
defined('IP2LOCATION_DIR') or define('IP2LOCATION_DIR', $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'ip2location' . DIRECTORY_SEPARATOR);
define('IP2LOCATION_REDIRECTION_ROOT', __DIR__ . DIRECTORY_SEPARATOR);

// For development usage
if (isset($_SERVER['DEV_MODE'])) {
	$_SERVER['REMOTE_ADDR'] = '8.8.8.8';
}

// Initial class
$ip2location_redirection = new IP2LocationRedirection();

register_activation_hook(__FILE__, [$ip2location_redirection, 'set_defaults']);

add_action('init', [$ip2location_redirection, 'start_session'], 1);
add_action('wp', [$ip2location_redirection, 'redirect']);
add_action('admin_enqueue_scripts', [$ip2location_redirection, 'plugin_enqueues']);
add_action('wp_ajax_update_ip2location_redirection_database', [$ip2location_redirection, 'download_database']);
add_action('admin_notices', [$ip2location_redirection, 'plugin_admin_notices']);
add_action('wp_ajax_ip2location_redirection_admin_notice', [$ip2location_redirection, 'plugin_dismiss_admin_notice']);
add_action('wp_footer', [$ip2location_redirection, 'footer']);

class IP2LocationRedirection
{
	protected $global_status = '';
	protected $logs = [];

	public function __construct()
	{
		// Make sure this plugin loaded as first priority.
		$wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR . '/$2', __FILE__);
		$this_plugin = plugin_basename(trim($wp_path_to_this_file));
		$active_plugins = get_option('active_plugins');
		$this_plugin_key = array_search($this_plugin, $active_plugins);

		if ($this_plugin_key) {
			array_splice($active_plugins, $this_plugin_key, 1);
			array_unshift($active_plugins, $this_plugin);
			update_option('active_plugins', $active_plugins);
		}

		// Check for IP2Location BIN directory
		if (!file_exists(IP2LOCATION_DIR)) {
			wp_mkdir_p(IP2LOCATION_DIR);
		}

		add_action('admin_menu', [$this, 'add_admin_menu']);
	}

	public function start_session()
	{
		if (headers_sent()) {
			return;
		}

		if (!session_id()) {
			session_start();
		}
	}

	public function add_admin_menu()
	{
		add_menu_page('Redirection', 'Redirection', 'manage_options', 'ip2location-redirection', [$this, 'admin_page'], 'dashicons-admin-ip2location', 31);
	}

	public function plugin_admin_notices()
	{
		if (get_user_meta(get_current_user_id(), 'ip2location_redirection_admin_notice', true) === 'dismissed') {
			return;
		}

		$current_screen = get_current_screen();

		if ($current_screen->parent_base == 'plugins') {
			if (is_plugin_active('ip2location-redirection/ip2location-redirection.php')) {
				echo '
					<div id="ip2location-redirection-notice" class="updated notice is-dismissible">
						<h2>IP2Location Redirection is almost ready!</h2>
						<p>Download and update IP2Location BIN database for accurate result.</p>
						<p>
							<a href="' . get_admin_url() . 'admin.php?page=ip2location-redirection&tab=settings" class="button button-primary"> Download Now </a>
							<a href="https://www.ip2location.com/?r=wordpress" class="button"> Learn more </a>
						</p>
					</div>
				';
			}
		}
	}

	public function plugin_enqueues($hook)
	{
		wp_enqueue_style('ip2location_redirection_admin_menu_styles', untrailingslashit(plugins_url('/', __FILE__)) . '/assets/css/style.css', []);

		if ($hook == 'toplevel_page_ip2location-redirection') {
			wp_enqueue_script('ip2location_redirection_admin_script', plugins_url('/assets/js/script.js', __FILE__), ['jquery'], null, true);
		} elseif (is_admin() && get_user_meta(get_current_user_id(), 'ip2location_redirection_admin_notice', true) !== 'dismissed') {
			wp_enqueue_script('ip2location_redirection_admin_script', plugins_url('/assets/js/notice-update.js', __FILE__), ['jquery'], '1.0', true);
			wp_localize_script('ip2location_redirection_admin_script', 'ip2location_redirection_admin', ['ip2location_redirection_admin_nonce' => wp_create_nonce('ip2location_redirection_admin_nonce')]);
		}
	}

	public function plugin_dismiss_admin_notice()
	{
		if (!isset($_POST['ip2location_redirection_admin_nonce'])) {
			wp_die();
		}

		update_user_meta(get_current_user_id(), 'ip2location_redirection_admin_notice', 'dismissed');
	}

	public function set_defaults()
	{
		if (get_option('ip2location_redirection_enabled') !== false) {
			return;
		}

		// Initial default settings
		update_option('ip2location_redirection_enabled', 1);
		update_option('ip2location_redirection_first_redirect', 0);
		update_option('ip2location_redirection_lookup_mode', 'bin');
		update_option('ip2location_redirection_api_key', '');
		update_option('ip2location_redirection_database', '');
		update_option('ip2location_redirection_rules', '[]');
		update_option('ip2location_redirection_noredirect_enabled', 0);
		update_option('ip2location_redirection_debug_log_enabled', 0);

		// Find BIN database in IP2Location directory
		$files = scandir(IP2LOCATION_DIR);

		foreach ($files as $file) {
			if (strtoupper(substr($file, -4)) == '.BIN') {
				update_option('ip2location_redirection_database', $file);
				break;
			}
		}
	}

	public function download_database()
	{
		WP_Filesystem();
		global $wp_filesystem;

		try {
			$code = (isset($_POST['database'])) ? $_POST['database'] : '';
			$token = (isset($_POST['token'])) ? $_POST['token'] : '';

			$working_dir = IP2LOCATION_DIR . 'working' . DIRECTORY_SEPARATOR;
			$zip_file = $working_dir . 'database.zip';

			// Remove existing working directory
			$wp_filesystem->delete($working_dir, true);

			// Create working directory
			$wp_filesystem->mkdir($working_dir);

			// Start downloading BIN database from IP2Location website.
			if (!class_exists('WP_Http')) {
				include_once ABSPATH . WPINC . '/class-http.php';
			}

			$request = new WP_Http();
			$response = $request->request('https://www.ip2location.com/download?' . http_build_query([
				'file'  => $code,
				'token' => $token,
			]), [
				'timeout' => 120,
			]);

			if ((isset($response->errors)) || (!(in_array('200', $response['response'])))) {
				$wp_filesystem->delete($working_dir, true);
				die('CONNECTION ERROR');
			}

			// Save downloaded package.
			$fp = fopen($zip_file, 'w');

			fwrite($fp, $response['body']);
			fclose($fp);

			if (filesize($zip_file) < 51200) {
				$message = file_get_contents($zip_file);
				$wp_filesystem->delete($working_dir, true);

				die($message);
			}

			// Unzip the package to working directory
			$result = unzip_file($zip_file, $working_dir);

			// Once extracted, delete the package.
			// unlink($zip_file);

			if (is_wp_error($result)) {
				$wp_filesystem->delete($working_dir, true);
				die('UNZIP ERROR');
			}

			// File the BIN database
			$bin_database = '';
			$files = scandir($working_dir);

			foreach ($files as $file) {
				if (strtoupper(substr($file, -4)) == '.BIN') {
					$bin_database = $file;
					break;
				}
			}

			// Move file to IP2Location directory
			$wp_filesystem->move($working_dir . $bin_database, IP2LOCATION_DIR . $bin_database, true);
			update_option('ip2location_redirection_database', $bin_database);

			// Remove working directory
			$wp_filesystem->delete($working_dir, true);

			update_option('ip2location_redirection_token', $token);

			die('SUCCESS');
		} catch (Exception $e) {
			die('ERROR');
		}

		die('ERROR');
	}

	public function admin_page()
	{
		if (!is_admin()) {
			return;
		}

		add_action('wp_enqueue_script', 'load_jquery');
		wp_enqueue_script('ip2location_redirection_chosen_js', 'https://cdnjs.cloudflare.com/ajax/libs/chosen/1.7.0/chosen.jquery.min.js', [], null, true);

		wp_enqueue_style('ip2location_redirection_chosen_css', esc_url_raw('https://cdnjs.cloudflare.com/ajax/libs/chosen/1.7.0/chosen.min.css'), [], null);
		wp_enqueue_style('ip2location_redirection_custom_css', plugins_url('/assets/css/custom.css', __FILE__), [], null);

		if (get_option('ip2location_redirection_lookup_mode') == 'bin') {
			// Get BIN database
			if (($database = $this->get_database_file()) !== null) {
				update_option('ip2location_redirection_database', $database);
			}

			if (($date = $this->get_database_date()) !== null) {
				if (strtotime($date) < strtotime('-2 months')) {
					$this->global_status = '
					<div id="message" class="error">
						<p><strong>WARNING</strong>: Your IP2Location database was outdated. We strongly recommend you to download the latest version for accurate result.</p>
					</div>';
				}
			}
		}

		if (class_exists('W3_Cache') || function_exists('wp_super_cache_init') || class_exists('Cache_Enabler') || class_exists('WpFastestCache') || class_exists('SC_Advanced_Cache') || class_exists('LiteSpeed_Cache') || class_exists('HyperCache')) {
			echo '
			<div class="wrap">
				<h1>IP2Location Redirection</h1>
				<div id="message" class="error">
					<p><strong>ERROR</strong>: You have WordPress cache plugin installed. Please deactivate the plugin in order IP2Location Redirection to work properly.</p>
				</div>
			</div>';
		} else {
			$tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';

			switch ($tab) {
				// IP Query
				case 'ip-query':
					$ip_query_status = '';

					$ip_address = (isset($_POST['ip_address'])) ? $_POST['ip_address'] : $this->get_ip();

					if (isset($_POST['submit'])) {
						if (!filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
							$ip_query_status = '
							<div id="message" class="error">
								<p><strong>ERROR</strong>: Please enter a valid IP address.</p>
							</div>';
						} else {
							$result = $this->get_location($ip_address, false);

							if (empty($result['country_code'])) {
								$ip_query_status = '
								<div id="message" class="error">
									<p><strong>ERROR</strong>: Unable to lookup IP address <strong>' . $ip_address . '</strong>.</p>
								</div>';
							} else {
								$ip_query_status = '
								<div id="message" class="updated">
									<p>IP address <code>' . $ip_address . '</code> belongs to <strong>' . $result['country_name'] . ' (' . $result['country_code'] . ')</strong>.</p>
								</div>';
							}
						}
					}

					echo '
					<div class="wrap">
						<h1>IP2Location Redirection</h1>

						' . $this->admin_tabs() . '

						' . $ip_query_status . '

						<form method="post" novalidate="novalidate">
							<table class="form-table">
								<tr>
									<th scope="row"><label for="ip_address">IP Address</label></th>
									<td>
										<input name="ip_address" type="text" id="ip_address" value="' . $ip_address . '" class="regular-text" />
										<p class="description">Enter a valid IP address to lookup for country information.</p>
									</td>
								</tr>
							</table>

							<p class="submit">
								<input type="submit" name="submit" id="submit" class="button button-primary" value="Lookup" />
							</p>
						</form>

						<div class="clear"></div>
					</div>';
					break;

				// Settings
				case 'settings':
					$settings_status = '';
					$web_service_status = '';

					$lookup_mode = (isset($_POST['lookup_mode'])) ? $_POST['lookup_mode'] : get_option('ip2location_redirection_lookup_mode');
					$api_key = (isset($_POST['api_key'])) ? $_POST['api_key'] : get_option('ip2location_redirection_api_key');

					if (isset($_POST['lookup_mode'])) {
						update_option('ip2location_redirection_lookup_mode', $lookup_mode);

						$settings_status .= '
						<div id="message" class="updated">
							<p>Changes saved.</p>
						</div>';
					}

					if (isset($_POST['api_key'])) {
						if (!class_exists('WP_Http')) {
							include_once ABSPATH . WPINC . '/class-http.php';
						}

						$request = new WP_Http();

						$response = $request->request('https://api.ip2location.com/v2/?' . http_build_query([
							'key'   => $api_key,
							'check' => 1,
						]), ['timeout' => 3]);

						if ((isset($response->errors)) || (!(in_array('200', $response['response'])))) {
							$web_service_status .= '
							<div id="message" class="error">
								<p><strong>ERROR</strong>: Error when accessing IP2Location web service gateway.</p>
							</div>';
						} else {
							$json = json_decode($response['body']);

							if (!preg_match('/^[0-9]+$/', $json->response)) {
								$web_service_status .= '
								<div id="message" class="error">
									<p><strong>ERROR</strong>: Invalid API key.</p>
								</div>';
							} else {
								update_option('ip2location_redirection_api_key', $api_key);

								$web_service_status = '
								<div id="message" class="updated">
									<p>IP2Location Web Service API key saved.</p>
								</div>';
							}
						}
					}

					$date = $this->get_database_date();

					if ($lookup_mode == 'bin' && !is_file(IP2LOCATION_DIR . get_option('ip2location_redirection_database'))) {
						$settings_status .= '
						<div id="message" class="error">
							<p><strong>ERROR</strong>: Unable to find the IP2Location BIN database! Please download the database at at <a href="https://www.ip2location.com/?r=wordpress" target="_blank">IP2Location commercial database</a> | <a href="https://lite.ip2location.com/?r=wordpress" target="_blank">IP2Location LITE database (free edition)</a>.</p>
						</div>';
					}

					echo '
					<div class="wrap">
						<h1>IP2Location Redirection</h1>

						' . $this->admin_tabs() . '

						<h2 class="title">General Settings</h2>

						' . $settings_status . '

						<form method="post" novalidate="novalidate">
							<table class="form-table">
								<tr>
									<th scope="row">
										<label for="lookup_mode">Lookup Mode</label>
									</th>
									<td>
										<fieldset>
											<legend class="screen-reader-text"><span>Lookup Mode</span></legend>
											<label><input type="radio" name="lookup_mode" id="lookup_mode_bin" value="bin"' . (($lookup_mode == 'bin') ? ' checked' : '') . ' /> IP2Location Binary Database</label><br />
											<label><input type="radio" name="lookup_mode" id="lookup_mode_ws" value="ws"' . (($lookup_mode == 'ws') ? ' checked' : '') . ' /> IP2Location Web Service</label>
										</fieldset>
									</td>
								</tr>
							</table>

							<p class="submit">
								<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" />
							</p>
						</form>

						<div id="bin_database">
							<h2 class="title">Database Information</h2>

							<table class="form-table">
								<tr>
									<th scope="row">
										<label>File Name</label>
									</th>
									<td>
										<div>' . ((!is_file(IP2LOCATION_DIR . get_option('ip2location_redirection_database'))) ? '<span class="dashicons dashicons-warning" title="Database file not found."></span>' : '') . get_option('ip2location_redirection_database') . '
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label>Database Date</label>
									</th>
									<td>
										' . (($date) ? $date : '-') . '
									</td>
								</tr>
							</table>

							<h2 class="title">Download & Update IP2Location BIN Database</h2>

							<div id="download_status"></div>

							<table class="form-table">
								<tr>
									<th scope="row">
										<label for="database_name">Database Name</label>
									</th>
									<td>
										<select name="database_name" id="database_name">
											<option value=""></option>
											<option value="DB1LITEBIN"> IP2Location LITE DB1</option>
											<option value="DB1BIN"> IP2Location DB1</option>
											<option value="DB1LITEBINIPV6">IP2Location LITE DB1 (IPv6)</option>
											<option value="DB1BINIPV6">IP2Location DB1 (IPv6)</option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="token">Download Token</label></th>
									<td>
										<input name="token" type="text" id="token" value="' . get_option('ip2location_redirection_token') . '" class="regular-text" />
										<p class="description">
											Get your download token from <a href="https://lite.ip2location.com/file-download" target="_blank">https://lite.ip2location.com/file-download</a> or <a href="https://www.ip2location.com/file-download" target="_blank">https://www.ip2location.com/file-download</a>.
											<br><br>
											If you failed to download the BIN database using this automated downloading tool, please follow the procedures below to update the BIN database manually.

											<ol>
												<li>
													Download the BIN database at <a href="https://www.ip2location.com/?r=wordpress" target="_blank">IP2Location commercial database</a> | <a href="https://lite.ip2location.com/?r=wordpress" target="_blank">IP2Location LITE database (free edition)</a>.</li>
												<li>
													Decompress the zip file and update the BIN database to <code>' . IP2LOCATION_DIR . '</code>.
												</li>
												<li>
													Once completed, please refresh the information by reloading the setting page.
												</li>
											</ol>
										</p>
										<p class="description">
											You may implement automated monthly database update as well. <a href="https://www.ip2location.com/resources/how-to-automate-ip2location-bin-database-download" target="_balnk">Learn more...</a>
										</p>
									</td>
								</tr>
							</table>

							<div id="ip2location-download-progress">
								<div class="loading-admin-ip2location"></div> Downloading...
							</div>

							<p class="submit">
								<input type="submit" name="download" id="download" class="button" value="Download Now" />
							</p>
						</div>

						<div id="ws_access">
							<h2 class="title">Web Service</h2>

							' . $web_service_status . '
							<form method="post" novalidate="novalidate">
								<table class="form-table">
									<tr>
										<th scope="row">
											<label for="api_key">API Key</label>
										</th>
										<td>
											<input name="api_key" type="text" id="api_key" value="' . $api_key . '" class="regular-text" />
											<p class="description">Your IP2Location <a href="https://www.ip2location.com/web-service" target="_blank">Web service</a> API key.</p>
										</td>
									</tr>';

									if (!empty($api_key)) {
										if (!class_exists('WP_Http')) {
											include_once ABSPATH . WPINC . '/class-http.php';
										}

										$request = new WP_Http();

										$response = $request->request('https://api.ip2location.com/v2/?' . http_build_query([
											'key'   => $api_key,
											'check' => 1,
										]), ['timeout' => 3]);

										if ((!isset($response->errors)) && ((in_array('200', $response['response'])))) {
											$json = json_decode($response['body']);

											if (preg_match('/^[0-9]+$/', $json->response)) {
												echo '
												<tr>
													<th scope="row">
														<label for="available_credit">Available Credit</label>
													</th>
													<td>
														' . number_format($json->response, 0, '', ',') . '
													</td>
												</tr>';
											}
										}
									}

								echo '
								</table>

								<p class="submit">
									<input type="submit" name="submit" id="submit" class="button" value="Save Changes" />
								</p>
							</form>
						</div>

						<div class="clear"></div>
					</div>';
					break;

				// General
				case 'general':
				default:
					$general_status = '';
					$rules = [];

					$enable_redirection = (isset($_POST['submit']) && isset($_POST['enable_redirection'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['enable_redirection']))) ? 0 : get_option('ip2location_redirection_enabled'));

					$first_redirect = (isset($_POST['submit']) && isset($_POST['first_redirect'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['first_redirect']))) ? 0 : get_option('ip2location_redirection_first_redirect'));

					$enable_noredirect = (isset($_POST['submit']) && isset($_POST['enable_noredirect'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['enable_noredirect']))) ? 0 : get_option('ip2location_redirection_noredirect_enabled'));

					$skip_bots = (isset($_POST['submit']) && isset($_POST['skip_bots'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['skip_bots']))) ? 0 : get_option('ip2location_redirection_skip_bots'));

					$enable_debug_log = (isset($_POST['submit']) && isset($_POST['enable_debug_log'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['enable_debug_log']))) ? 0 : get_option('ip2location_redirection_debug_log_enabled'));

					if (isset($_POST['submit'])) {
						if (isset($_POST['country_codes']) && is_array($_POST['country_codes'])) {
							$check_list = [];
							$index = 0;

							foreach ($_POST['country_codes'] as $country_codes) {
								$country_codes = explode(';', $country_codes);

								// Invalid inputs, ignore silently.
								if (empty($_POST['from'][$index]) || ($_POST['from'][$index] == 'url' && empty($_POST['url_from'][$index])) || ($_POST['to'][$index] == 'url' && empty($_POST['url_to'][$index]))) {
									++$index;
									continue;
								}

								// From and destination cannot be same.
								if ($_POST['from'][$index] != 'url' && $_POST['from'][$index] != 'domain' && $_POST['from'][$index] == $_POST['to'][$index]) {
									++$index;
									continue;
								}

								// Domain redirection must redirect from domain to domain
								if (($_POST['from'][$index] == 'domain' && $_POST['to'][$index] != 'domain') || $_POST['to'][$index] == 'domain' && $_POST['from'][$index] != 'domain') {
									++$index;
									continue;
								}

								if ($_POST['from'][$index] != 'url') {
									$_POST['url_from'][$index] = '';
								}

								if ($_POST['to'][$index] != 'url') {
									$_POST['url_to'][$index] = '';
								}

								if ($_POST['from'][$index] != 'domain' || $_POST['to'][$index] != 'domain') {
									$_POST['domain_from'][$index] = '';
									$_POST['domain_to'][$index] = '';
								}

								// Validate domain name
								if ($_POST['from'][$index] == 'domain' && !preg_match('/^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/', $_POST['domain_from'][$index])) {
									$general_status .= '
									<div id="message" class="error">
										<p><strong>' . $_POST['domain_from'][$index] . '</strong> is not a domain name.</p>
									</div>';

									break;
								}

								if ($_POST['to'][$index] == 'domain' && !preg_match('/^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/', $_POST['domain_to'][$index])) {
									$general_status .= '
									<div id="message" class="error">
										<p><strong>' . $_POST['domain_to'][$index] . '</strong> is not a domain name.</p>
									</div>';

									break;
								}

								// Both URL from and to cannot be same.
								if ($_POST['from'][$index] == 'url' && $_POST['to'][$index] == 'url' && $_POST['url_from'][$index] == $_POST['url_to'][$index]) {
									$general_status .= '
									<div id="message" class="error">
										<p>Target URL and destination URL cannot be same.</p>
									</div>';

									break;
								}

								// Both domain from and to cannot be same.
								if ($_POST['from'][$index] == 'domain' && $_POST['to'][$index] == 'domain' && $_POST['domain_from'][$index] == $_POST['domain_to'][$index]) {
									$general_status .= '
									<div id="message" class="error">
										<p>Target domain and destination domain <strong>' . $_POST['domain_from'][$index] . '</strong> cannot be same.</p>
									</div>';

									break;
								}

								if ($_POST['from'][$index] == 'url' && !filter_var($_POST['url_from'][$index], FILTER_VALIDATE_URL)) {
									$general_status .= '
									<div id="message" class="error">
										<p><strong>' . $_POST['url_form'][$index] . '</strong> is not a valid URL.</p>
									</div>';

									break;
								}

								if ($_POST['to'][$index] == 'url' && !filter_var($_POST['url_to'][$index], FILTER_VALIDATE_URL)) {
									$general_status .= '
									<div id="message" class="error">
										<p><strong>' . $_POST['url_to'][$index] . '</strong> is not a valid URL.</p>
									</div>';

									break;
								}

								$idx = 0;
								foreach ($country_codes as $country_code) {
									if ($_POST['exclude'][$index]) {
										$country_codes[$idx] = (substr($country_code, 0, 1) == '-') ? $country_code : ('-' . $country_code);
									} else {
										$country_codes[$idx] = (substr($country_code, 0, 1) == '-') ? substr($country_code, 1) : $country_code;
									}

									++$idx;
								}

								if ($_POST['from'][$index] == 'domain') {
									$_POST['url_from'][$index] = $_POST['domain_from'][$index];
									$_POST['url_to'][$index] = $_POST['domain_to'][$index];

									if ($_POST['keep_query'][$index]) {
										$_POST['url_from'][$index] = '*' . $_POST['url_from'][$index];
									}
								}

								$rules[] = [implode(';', $country_codes), $_POST['from'][$index], $_POST['to'][$index], $_POST['url_from'][$index], $_POST['url_to'][$index], $_POST['status_code'][$index]];

								++$index;
							}
						}

						if (empty($general_status)) {
							update_option('ip2location_redirection_enabled', $enable_redirection);
							update_option('ip2location_redirection_first_redirect', $first_redirect);
							update_option('ip2location_redirection_rules', json_encode($rules));
							update_option('ip2location_redirection_noredirect_enabled', $enable_noredirect);
							update_option('ip2location_redirection_skip_bots', $skip_bots);
							update_option('ip2location_redirection_debug_log_enabled', $enable_debug_log);

							$general_status = '
							<div id="message" class="updated">
								<p>Changes saved.</p>
							</div>';
						}
					}

					echo '
					<div class="wrap">
						<h1>IP2Location Redirection</h1>

						' . $this->admin_tabs() . '

						' . $general_status . '

						<form method="post" novalidate="novalidate">
							<table class="form-table">
								<tr>
									<td>
										<label for="enable_redirection">
											<input type="checkbox" name="enable_redirection" id="enable_redirection"' . (($enable_redirection) ? ' checked' : '') . '>
											Enable Redirection
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="first_redirect">
											<input type="checkbox" name="first_redirect" id="first_redirect"' . (($first_redirect) ? ' checked' : '') . '>
											Redirect on first visit only
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<table class="wp-list-table widefat striped">
											<thead>
												<tr>
													<th>Location</th>
													<th>From</th>
													<th>Destination</th>
													<th>Redirection Code</th>
													<td>&nbsp;</td>
												</tr>
											</thead>
											<tbody id="rules">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<label for="enable_noredirect">
											<input type="checkbox" name="enable_noredirect" id="enable_noredirect"' . (($enable_noredirect) ? ' checked' : '') . '>
											Skip redirection if <strong>noredirect=true</strong> found in URL. For example, https://www.example.com/?page=1&<code>noredirect=true</code>
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="skip_bots">
											<input type="checkbox" name="skip_bots" id="skip_bots"' . (($skip_bots) ? ' checked' : '') . '>
											Do not redirect bots and crawlers.
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="enable_debug_log">
											<input type="checkbox" name="enable_debug_log" id="enable_debug_log"' . (($enable_debug_log) ? ' checked' : '') . '>
											Enable debug log for development purpose.
										</label>
									</td>
								</tr>
							</table>

							<p class="submit">
								<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" />
							</p>
						</form>

						<div class="clear"></div>
					</div>';

					$page_list = [];
					$post_list = [];
					$rule_list = '[["","","","","",301]]';

					if (($data = json_decode(get_option('ip2location_redirection_rules'))) !== null) {
						$rule_list = '[';

						foreach ($data as $values) {
							if (count($values) == 5) {
								$rule_list .= '["' . $values[0] . '","' . $values[1] . '","' . $values[2] . '","","' . $values[3] . '",' . $values[4] . '],';
							} else {
								$rule_list .= '["' . $values[0] . '","' . $values[1] . '","' . $values[2] . '","' . $values[3] . '","' . $values[4] . '",' . $values[5] . '],';
							}
						}

						$rule_list = rtrim($rule_list, ',');
						$rule_list .= ']';

						if (empty($data)) {
							$rule_list = '[["","","","","",301]]';
						}
					}

					$pages = get_pages(['numberposts' => -1, 'post_status' => 'publish']);
					$posts = get_posts(['numberposts' => -1, 'post_status' => 'publish']);

					if (count($pages) > 0) {
						foreach ($pages as $page) {
							$page_list[] = ['page_id' => 'page-' . $page->ID, 'page_title' => 'Page/' . (($page->post_title) ? $page->post_title : '(No Title)')];
						}
					}

					if (count($posts) > 0) {
						foreach ($posts as $post) {
							$post_list[] = ['post_id' => 'post-' . $post->ID, 'post_title' => 'Post/' . (($post->post_title) ? $post->post_title : '(No Title)')];
						}
					}

					$scripts = [];

					$scripts[] = '';
					$scripts[] = '<script>';
					$scripts[] = 'var rules = ' . $rule_list . ';';
					$scripts[] = 'var pages = ' . json_encode($page_list) . ';';
					$scripts[] = 'var posts = ' . json_encode($post_list) . ';';
					$scripts[] = '</script>';

					echo implode("\n", $scripts);
			}

			echo '
			<p>If you like this plugin, please leave us a <a href="https://wordpress.org/support/plugin/ip2location-redirection/reviews/">rating</a>. A huge thanks in advance!</p>';
		}
	}

	public function redirect()
	{
		// Overwrite headers to prevent content being cached
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');
		header('Pragma: no-cache');

		if (!get_option('ip2location_redirection_enabled')) {
			$this->write_debug_log('Redirection is disabled.');
			$this->save_debug_log();

			return;
		}

		if (isset($_SERVER['REQUEST_URI']) && preg_match('/wc-ajax/', $_SERVER['REQUEST_URI'])) {
			$this->write_debug_log('Result       : Redirection aborted. (Reason: WooCommerce AJAX page)');
			$this->save_debug_log();

			return;
		}

		$this->write_debug_log('URL          : ' . $this->get_current_url());

		if (is_admin() || current_user_can('administrator')) {
			$this->write_debug_log('Result       : Redirection aborted. (Reason: Logged as administrator)');
			$this->save_debug_log();

			return;
		}

		if (get_option('ip2location_redirection_skip_bots') && $this->is_bot()) {
			$this->write_debug_log('Result       : Redirection aborted. (Reason: Web crawler detected)');
			$this->save_debug_log();

			return;
		}

		if (get_option('ip2location_redirection_noredirect_enabled')) {
			if (isset($_GET['noredirect']) && trim($_GET['noredirect']) == 'true') {
				$this->write_debug_log('Result       : Redirection aborted. (Reason: Parameter "noredirect" is found)');
				$this->save_debug_log();

				return;
			}
		}

		if (get_option('ip2location_redirection_first_redirect')) {
			if (isset($_COOKIE['ip2location_redirection_first_visit'])) {
				$this->write_debug_log('Result       : Redirection aborted. (Reason: Not first visit)');
				$this->save_debug_log();

				return;
			}

			setcookie('ip2location_redirection_first_visit', time(), strtotime('+24 hours'));
		}

		if (($data = json_decode(get_option('ip2location_redirection_rules'))) !== null) {
			$result = $this->get_location($this->get_ip());

			if (empty($result['country_code'])) {
				$this->write_debug_log('Result       : Redirection aborted. (Reason: Unable to identify visitor country)');
				$this->save_debug_log();

				return;
			}

			foreach ($data as $values) {
				$country_codes = explode(';', $values[0]);
				$page_from = $values[1];
				$page_to = $values[2];

				if (count($values) == 5) {
					$url_from = '';
					$url_to = $values[3];
					$http_code = $values[4];
				} else {
					$url_from = $values[3];
					$url_to = $values[4];
					$http_code = $values[5];
				}

				if ($this->is_country_match($result['country_code'], $country_codes)) {
					$this->write_debug_log('Matched Rule : [' . $result['country_code'] . '] is listed in [' . implode(', ', $country_codes) . ']');

					if ($page_from == 'domain') {
						// Keep query string
						if (substr($url_from, 0, 1) == '*') {
							if (substr($url_from, 1) == $_SERVER['HTTP_HOST']) {
								$this->redirect_to(str_replace(substr($url_from, 1), $url_to, $this->get_current_url()), $http_code);
							}
						} else {
							if ($url_from == $_SERVER['HTTP_HOST']) {
								$this->redirect_to(str_replace($url_from, $url_to, $this->get_current_url(false)), $http_code);
							}
						}
					}

					if ($page_from == 'any' || ($page_from == 'home' && $this->is_home()) || ($page_from == 'url' && trim($this->get_current_url(), '/') == trim($url_from, '/'))) {
						if ($page_to == 'url') {
							if ($_SERVER['QUERY_STRING']) {
								parse_str($_SERVER['QUERY_STRING'], $query_string);

								unset($query_string['page_id']);
								unset($query_string['p']);

								$data = parse_url($url_to);

								$post_query = [];

								if (isset($data['query'])) {
									parse_str($data['query'], $post_query);
								}

								$queries = array_merge($query_string, $post_query);

								unset($queries['p']);

								$target_url = $this->build_url($data['scheme'], $data['host'], $data['path'], $queries);

								if ($this->get_current_url() == $target_url) {
									return;
								}

								$this->redirect_to($target_url, $http_code);
							}

							$this->redirect_to($url_to, $http_code);
						}

						list($type, $id) = explode('-', $page_to);

						// Prevent infinite loop
						if ($id == get_the_ID()) {
							$this->write_debug_log('Result       : Redirection aborted. (Reason: Same page redirection)');
							$this->save_debug_log();

							return;
						}

						if (rtrim($this->get_current_url(), '/') == rtrim(get_permalink($id), '/')) {
							$this->write_debug_log('Result       : Redirection aborted. (Reason: Same page redirection)');
							$this->save_debug_log();

							return;
						}

						$this->redirect_to(get_permalink($id), $http_code);
					}

					if (is_404()) {
						continue;
					}

					if (strpos($page_from, '-') === false) {
						continue;
					}

					list($type, $id) = explode('-', $page_from);

					if ($id == get_the_ID()) {
						if ($page_to == 'url') {
							if ($_SERVER['QUERY_STRING']) {
								parse_str($_SERVER['QUERY_STRING'], $query_string);

								unset($query_string['page_id']);
								unset($query_string['p']);
								unset($query_string['add-to-cart']);

								$data = parse_url($url_to);

								$post_query = [];

								if (isset($data['query'])) {
									parse_str($data['query'], $post_query);
								}

								$queries = array_merge($post_query, $query_string);

								unset($queries['p']);

								$this->redirect_to($this->build_url($data['scheme'], $data['host'], $data['path'], $queries), $http_code);
							}

							$this->redirect_to($url_to, $http_code);
						}

						list($type, $id) = explode('-', $page_to);

						if ($_SERVER['QUERY_STRING']) {
							parse_str($_SERVER['QUERY_STRING'], $query_string);

							unset($query_string['page_id']);
							unset($query_string['p']);
							unset($query_string['add-to-cart']);

							$post_url = get_permalink($id);
							$data = parse_url($post_url);

							$post_query = [];

							if (isset($data['query'])) {
								parse_str($data['query'], $post_query);
							}

							$queries = array_merge($post_query, $query_string);

							$this->redirect_to($this->build_url($data['scheme'], $data['host'], $data['path'], $queries), $http_code);
						}

						$this->redirect_to(get_permalink($id), $http_code);
					}
				}

				$this->write_debug_log('Result       : URL not matched');
				$this->save_debug_log();
			}
		}
	}

	public function footer()
	{
		echo "<!--\n";
		echo "The IP2Location Redirection is using IP2Location LITE geolocation database. Please visit https://lite.ip2location.com for more information.\n";
		echo "-->\n";
	}

	public function write_debug_log($message)
	{
		if (!get_option('ip2location_redirection_debug_log_enabled')) {
			return;
		}

		$this->logs[] = implode("\t", [
			gmdate('Y-m-d H:i:s'),
			$this->get_ip(),
			$message,
		]);
	}

	public function save_debug_log()
	{
		if (!get_option('ip2location_redirection_debug_log_enabled')) {
			return;
		}

		if (empty($this->logs)) {
			return;
		}

		error_log(implode("\n", $this->logs) . "\n\n", 3, IP2LOCATION_REDIRECTION_ROOT . 'debug.log');

		$this->logs = [];
	}

	private function is_country_match($geo_country, $rule_country)
	{
		if (is_array($rule_country)) {
			$index = 0;

			foreach ($rule_country as $country) {
				if ($geo_country == $country) {
					return true;
				}

				if (substr($country, 0, 1) == '-' && substr($country, 1) != $geo_country) {
					++$index;
				}

				if ($index == count($rule_country)) {
					return true;
				}
			}

			return false;
		}

		if ($geo_country == $rule_country) {
			return true;
		}

		if (substr($rule_country, 0, 1) == '-' && substr($rule_country, 1) != $geo_country) {
			return true;
		}

		return false;
	}

	private function is_bot()
	{
		if (preg_match('/baidu|bingbot|facebookexternalhit|googlebot|-google|ia_archiver|msnbot|naverbot|pingdom|seznambot|slurp|teoma|twitter|yandex|yeti|linkedinbot|pinterest/i', $this->get_user_agent())) {
			return true;
		}

		return false;
	}

	private function get_user_agent()
	{
		return (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : null;
	}

	private function admin_tabs()
	{
		$tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';

		return '
		' . $this->global_status . '
		<h2 class="nav-tab-wrapper">
			<a href="' . admin_url('admin.php?page=ip2location-redirection&tab=general') . '" class="nav-tab' . (($tab == 'general') ? ' nav-tab-active' : '') . '">General</a>
			<a href="' . admin_url('admin.php?page=ip2location-redirection&tab=ip-query') . '" class="nav-tab' . (($tab == 'ip-query') ? ' nav-tab-active' : '') . '">IP Query</a>
			<a href="' . admin_url('admin.php?page=ip2location-redirection&tab=settings') . '" class="nav-tab' . (($tab == 'settings') ? ' nav-tab-active' : '') . '">Settings</a>
		</h2>';
	}

	private function get_ip()
	{
		// Get server IP address
		$server_ip = (isset($_SERVER['SERVER_ADDR'])) ? $_SERVER['SERVER_ADDR'] : '';

		// If website is hosted behind CloudFlare protection.
		if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
			return $_SERVER['HTTP_CF_CONNECTING_IP'];
		}

		if (isset($_SERVER['X-Real-IP']) && filter_var($_SERVER['X-Real-IP'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
			return $_SERVER['X-Real-IP'];
		}

		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = trim(current(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));

			if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) && $ip != $server_ip) {
				return $ip;
			}
		}

		return $_SERVER['REMOTE_ADDR'];
	}

	private function is_home()
	{
		if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] == '/') {
			return true;
		}

		return is_home();
	}

	private function build_url($scheme, $host, $path, $queries)
	{
		return $scheme . '://' . $host . (($path) ? $path : '/') . (($queries) ? ('?' . http_build_query($queries)) : '');
	}

	private function get_current_url($add_query = true)
	{
		global $wp;

		$current_url = add_query_arg($_SERVER['QUERY_STRING'], '', home_url($wp->request));

		$data = parse_url($current_url);

		$queries = [];

		if (isset($data['query'])) {
			parse_str($data['query'], $queries);
		}

		return $this->build_url($data['scheme'], $data['host'], ((isset($data['path'])) ? $data['path'] : ''), (($add_query) ? $queries : []));
	}

	private function redirect_to($url, $mode)
	{
		$this->write_debug_log('Result       : Redirected to ' . $url);
		$this->save_debug_log();

		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $url, true, $mode);

		die;
	}

	private function get_location($ip, $use_cache = true)
	{
		// Read result from session to prevent duplicate lookup.
		if (isset($_SESSION[$ip . '_country_code']) && !empty($_SESSION[$ip . '_country_code']) && $use_cache) {
			$this->write_debug_log('Lookup By    : Session Cache');
			$this->write_debug_log('Country      : ' . $_SESSION[$ip . '_country_name'] . ' [' . $_SESSION[$ip . '_country_code'] . ']');

			return [
				'country_code' => $_SESSION[$ip . '_country_code'],
				'country_name' => $_SESSION[$ip . '_country_name'],
			];
		}

		switch (get_option('ip2location_redirection_lookup_mode')) {
			// IP2Location Web Service
			case 'ws':
				if (!class_exists('WP_Http')) {
					include_once ABSPATH . WPINC . '/class-http.php';
				}

				$this->write_debug_log('Lookup By    : Web Service');

				$request = new WP_Http();
				$response = $request->request('https://api.ip2location.com/v2/?' . http_build_query([
					'key' => get_option('ip2location_redirection_api_key'),
					'ip'  => $ip,
				]), ['timeout' => 3]);

				if ((isset($response->errors)) || (!(in_array('200', $response['response'])))) {
					$this->write_debug_log('ERROR: Web service connection error.');

					return [
						'country_code' => '',
						'country_name' => '',
					];
				}

				$json = json_decode($response['body']);

				// Store result into session for later use.
				$_SESSION[$ip . '_country_code'] = $json->country_code;
				$_SESSION[$ip . '_country_name'] = $this->get_country_name($json->country_code);

				$this->write_debug_log('Country      : ' . $_SESSION[$ip . '_country_name'] . ' [' . $_SESSION[$ip . '_country_code'] . ']');

				return [
					'country_code' => $_SESSION[$ip . '_country_code'],
					'country_name' => $_SESSION[$ip . '_country_name'],
				];
			break;

			// Local BIN database
			default:
			case 'bin':
				$this->write_debug_log('Lookup By    : BIN Database');

				// Make sure IP2Location database is exist.
				if (!is_file(IP2LOCATION_DIR . get_option('ip2location_redirection_database'))) {
					$this->write_debug_log('ERROR: "' . get_option('ip2location_redirection_database') . '" not found');

					return;
				}

				if (!class_exists('IP2Location\\Database')) {
					require_once IP2LOCATION_REDIRECTION_ROOT . 'class.IP2Location.php';
				}

				// Create IP2Location object.
				$db = new \IP2Location\Database(IP2LOCATION_DIR . get_option('ip2location_redirection_database'), \IP2Location\Database::FILE_IO);

				// Get geolocation by IP address.
				$response = $db->lookup($ip, \IP2Location\Database::ALL);

				// Store result into session for later use.
				$_SESSION[$ip . '_country_code'] = $response['countryCode'];
				$_SESSION[$ip . '_country_name'] = $response['countryName'];

				$this->write_debug_log('Country      : ' . $_SESSION[$ip . '_country_name'] . ' [' . $_SESSION[$ip . '_country_code'] . ']');

				return [
					'country_code' => $_SESSION[$ip . '_country_code'],
					'country_name' => $_SESSION[$ip . '_country_name'],
				];
			break;
		}
	}

	private function get_country_name($code)
	{
		$countries = ['AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia, Plurinational State of', 'BQ' => 'Bonaire, Sint Eustatius and Saba', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'CV' => 'Cabo Verde', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CD' => 'Congo, The Democratic Republic of The', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Cote D\'ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands (Malvinas)', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island and Mcdonald Islands', 'VA' => 'Holy See', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran, Islamic Republic of', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KP' => 'Korea, Democratic People\'s Republic of', 'KR' => 'Korea, Republic of', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Lao People\'s Democratic Republic', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia, The Former Yugoslav Republic of', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia, Federated States of', 'MD' => 'Moldova, Republic of', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestine, State of', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena, Ascension and Tristan Da Cunha', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin (French Part)', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent and The Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten (Dutch Part)', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia and The South Sandwich Islands', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Eswatini', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syrian Arab Republic', 'TW' => 'Taiwan, Province of China', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania, United Republic of', 'TH' => 'Thailand', 'TL' => 'Timor-Leste', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UM' => 'United States Minor Outlying Islands', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VE' => 'Venezuela, Bolivarian Republic of', 'VN' => 'Viet Nam', 'VG' => 'Virgin Islands, British', 'VI' => 'Virgin Islands, U.S.', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe'];

		return (isset($countries[$code])) ? $countries[$code] : '';
	}

	private function get_database_file()
	{
		// Find any .BIN files in current directory.
		$files = scandir(IP2LOCATION_DIR);

		foreach ($files as $file) {
			if (strtoupper(substr($file, -4)) == '.BIN') {
				return $file;
			}
		}
	}

	private function get_database_date()
	{
		if (!class_exists('IP2Location\\Database')) {
			require_once IP2LOCATION_REDIRECTION_ROOT . 'class.IP2Location.php';
		}

		if (!is_file(IP2LOCATION_DIR . get_option('ip2location_redirection_database'))) {
			return;
		}

		$obj = new \IP2Location\Database(IP2LOCATION_DIR . get_option('ip2location_redirection_database'), \IP2Location\Database::FILE_IO);

		return date('Y-m-d', strtotime(str_replace('.', '-', $obj->getDatabaseVersion())));
	}
}
