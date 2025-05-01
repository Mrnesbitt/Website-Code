<?php
define( 'WP_CACHE', true );
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u519559472_8Lrqf' );

/** Database username */
define( 'DB_USER', 'u519559472_jDyhh' );

/** Database password */
define( 'DB_PASSWORD', '3T0nfnF9Op' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'Qik&f1C80@=Q!.Bc%]g_7z!N=H&d0sn8Lfk1ZHr{ip2U%&H.r-IPAaj-udc5!)Ox' );
define( 'SECURE_AUTH_KEY',   '5Cp/=3+<-KVn2Bf<RAU,@Wt<.`UoWi<pZ05~.Nw5|z=t|w w)(U3k!k=NZItkorb' );
define( 'LOGGED_IN_KEY',     'l? o}yDA{ UHIFke}Ujub5W 39N$u,}_DB=S?:{6>H]CPSTt~WT89Vo;OU8|bg|p' );
define( 'NONCE_KEY',         '!:bcGa*]~yKsh%E2KzHjI-jh6#$QEn3f5.:79|qSz4:fuoG/HR4J.[eN~8B3NZgx' );
define( 'AUTH_SALT',         'i[{mo4?U>ka38,0=WV3:o=x!#3#Vq?Y9-R}1.nBQIy9UY~LzCkP&}AfS?cZ_9Ys~' );
define( 'SECURE_AUTH_SALT',  'Wmj<p=O.=l0MX(/NVMzCgN6 95s@D?#ws:o;ZFG`ImUki|=qE={rkq@4X>#^ 4!l' );
define( 'LOGGED_IN_SALT',    'h;$&Zx&YYek x9!&sH<Ujs[@|]S,|yBex2]/PVg?NP@FI<#fiy&+IR5}d{+zm]i6' );
define( 'NONCE_SALT',        'b@_!}6bSbZB%2{JT^~(1VysR|e~/9ljWAf^j,A0G_M7-}BRa_Z7nW?K h/IX.Ag2' );
define( 'WP_CACHE_KEY_SALT', '.I%7q3;<P*57v@Z;BZ9oJs+*eD*yNQD9_^:~4K*+8f!B{G] h]j20#9XTIaXXXpz' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', '1247d6738636776b6a18021c6530a0bf' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
