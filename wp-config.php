<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

if (strpos($_SERVER['HTTP_HOST'], 'dev.livestyled.com') !== false) {
	$config_file = 'wp-br-config/wp-config.development.php';
} elseif (strpos($_SERVER['HTTP_HOST'], 'livestyled.staging.wearebigrock.com') !== false) {
	$config_file = 'wp-br-config/wp-config.staging.php';
} else {
	$config_file = 'wp-br-config/wp-config.production.php';
}
$path = dirname(__FILE__) . '/';
if (file_exists($path . $config_file)) {
	require_once $path . $config_file;
}


define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

define( 'AUTH_KEY', '`LMgQaurhi-8-V>R?ogNZjRs/1q%bYpgCX2#-.@}3znxt8JwW?9>/bI?ulT5A+EF' );
define( 'SECURE_AUTH_KEY', '9D+M=2;xl2J|:t3(0m^afkslnCR]EXnrkAZAMf*,irwJQ5c y+ZV,^@][lAu7MDZ' );
define( 'LOGGED_IN_KEY', '|{#(#,YR?E%Xa,ydSJM]tz_Z T*hy&Wf.B[xJl(&C`Xz/uLV>~}Uk741{<P&:dC!' );
define( 'NONCE_KEY', 'sH7P|}T1];~#gbdUK.-STgI*f{B^yYOv>Z|+c.K@Oh(//c.hl_V%4RGs(hlS^f=(' );
define( 'AUTH_SALT', '+`DD*tz}kJt(lf6m};mh1s1m+C@~vw;.L3OfzxiAO0Q%EW4<[%lm<ngl|JZuFw%F' );
define( 'SECURE_AUTH_SALT', 'E;_Vk+gd0G}3#D}aOZjO6jc_F+q,U.D2:/%YQ4,-sMlJV.{K4])+HD,@wm#TT:_8' );
define( 'LOGGED_IN_SALT', 'Y%:uQ+XdvXm-2]7D=A;/+PbR{ZIgK[$%c~;S[pI-8d2T=t[q/g$+?+O_:KyO4[tc' );
define( 'NONCE_SALT',' yj3|(-i5Mn<,JnDo,-d2ht+B3|x8Hp(&LQZ5h?z=p~(q[5Q`oD0-=f-Qix)=Wt^' );
$table_prefix  = 'br19_wp_';
define( 'WPLANG', 'en_GB' );
define( 'WP_MEMORY_LIMIT', '128M' );
define( 'WP_MAX_MEMORY_LIMIT', '256M' );
define( 'WP_POST_REVISIONS', 2 );
define( 'EMPTY_TRASH_DAYS', 10 );
define( 'AUTOSAVE_INTERVAL', 160 );


/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
