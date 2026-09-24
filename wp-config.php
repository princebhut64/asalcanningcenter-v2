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
define( 'DB_NAME', 'db-asalcanningcenter' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         ':a|ndtnN`t+7(&>Ps%.ppY=As%HFuIF;x%F5FpAAYuTe,!TQOf6n sy5lUUbPazJ' );
define( 'SECURE_AUTH_KEY',  'J9?39MS]~&`tLaxGxD VQI_l?@+AC G6V?m)~L3>n3eV({]Dx&3?N)M+7ym9t,ka' );
define( 'LOGGED_IN_KEY',    '37AW>Foa?uL5N3]+eQ+G6KGP~v0RHc~da60Jv.njr>zhzf3ty](c0VxmD[I!qtu6' );
define( 'NONCE_KEY',        '/Q*.Wg*Bj)P480op<[?g7Y? GPq%+%Pb!sZ45LVod_>rZK4H|I4Z=A;HUB969/KN' );
define( 'AUTH_SALT',        'HEsO6aru$<jiPP 6M)*PHbqwuKdNebQ/6Mz$l?>; M5wr?_3/~=vj5v?M95/xnNu' );
define( 'SECURE_AUTH_SALT', 'nNHyscK[J= Cc3O[BeN11BIT}8{IH,%p6m<%-:y0y%nx}zSMhef>Adq-%]W%TZF0' );
define( 'LOGGED_IN_SALT',   'yva2K=tSXOlfG=Z`/X@V|Rhg0=mn!/wE:KGYQx4*48A&7hI<i74_CRb*#j@hy^-e' );
define( 'NONCE_SALT',       'p}4)da5j`$C:^}}#A:d(^kQ`>d^W!EFnTCHHC^[ta]2rR=4*7y400PTi.5>|adx,' );

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
$table_prefix = 'asc_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
