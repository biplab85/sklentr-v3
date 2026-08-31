<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'sklentr_v3' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1:3306' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'XYj0kWlN-E|Y(h4dU:;r)gx;s9,UV<:W]|(!/ )^AI`*u[ZO/GNS3ZJUDN6opw+/');
define('SECURE_AUTH_KEY',  '#sIt4%%<&(dWUT{$,@=/FE6DY2I03^sF&4T0oDI.D(g`NjZERpqJ1D?7%o6PN@mp');
define('LOGGED_IN_KEY',    'l80@e3,/:fgViYMmhNui~/6cW0,hRgs=/0#`igXn+Z1.RF|b_H=7|)xwav3r1E<x');
define('NONCE_KEY',        'gkoH KQ|&oh.xnj5EtOx)E+AjIn+8r{`K9>->5x)C#1^zNslay4TX@!T7WV]i-Y+');
define('AUTH_SALT',        's,M%$t.q6`0&<Yh9d=G]9AM7&x|?H3VDCn+5A^pIg]bz4N$~PC-[^Z8>Y=2Mw4Ky');
define('SECURE_AUTH_SALT', 'wNF9is@i?W%}~_3u_qZ]#r6r?u;oQ!sU6#{tt?RAg3JWajw}?]Ksar{Y~qI.|suo');
define('LOGGED_IN_SALT',   'VKd)a,{C|J+U&f_Y*q]ag=B}78.3mp(0YDB|+ool^/0-KXToHpyP,}!hw:VD&_zJ');
define('NONCE_SALT',       'l1ikXl_<rJpx(#MT%$Z>x^96Vovp!l(<y}HLZ>4BZHFk9M|0wa{`%3N6Fni&]Ww+');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'skv3_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
