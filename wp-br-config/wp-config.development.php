<?php
/**
 * Development environment config settings
 *
 * Enter any WordPress config settings that are specific to this environment
 * in this file.
 *
 * @package    Studio 24 WordPress Multi-Environment Config
 * @version    2.0.0
 * @author     Studio 24 Ltd  <hello@studio24.net>
 */

define( 'DB_HOST', 'localhost' );
define( 'DB_NAME', 'livestyled_dev' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'root' );
//define( 'DB_PASSWORD', 'mysql' );

//** DO NOT UNCOMMENT DEBUGGING FROM DEV, ITS IMPORTANT NOTHING GOES LIVE WITH ERRORS */
define( 'WP_DEBUG', true );
@error_reporting( E_ALL );
@ini_set( 'log_errors', true );
@ini_set( 'log_errors_max_len', '0' );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );
define( 'CONCATENATE_SCRIPTS', false );
define( 'SAVEQUERIES', true );
define( 'SCRIPT_DEBUG', false );
define( 'WP_CACHE', false );
define( 'WP_AUTO_UPDATE_CORE', false );
define('DOMAIN_CURRENT_SITE', 'dev.livestyled.com');