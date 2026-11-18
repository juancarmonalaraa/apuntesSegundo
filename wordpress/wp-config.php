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
define( 'DB_NAME', 'wordpress' );

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
define( 'AUTH_KEY',         'sHnOY!O* &&~nMhD}HTR)|M6;9~f{34yruSf2eJ9H_haMgHEDE[DX;>|9%-+K,f[' );
define( 'SECURE_AUTH_KEY',  '-Gz)u2h.emyU^]TqB390J9U$RmlS)%yDeow@D&@)Bf?CuSD/rDZ0zP3#DEuxK~`3' );
define( 'LOGGED_IN_KEY',    '9i:ipXQ+7iM%uO0.k+Rr+pp~Ck8Isx*$01e`wxUvKg{/;8i@%osM|jn~?TM>ZJCf' );
define( 'NONCE_KEY',        '_=[m2kh$|qG sSjbEp3VOiB.v=&7,ns<~CMl[lt.1z0EPk,=0s3sNFNJPT)E;bAi' );
define( 'AUTH_SALT',        'V*`M< l-P9X*Sn*&M8FX&Rcm4r)#3;P2(R;tt`[B%Fxs4Y^&&5`x3Z^2_ek!7G|1' );
define( 'SECURE_AUTH_SALT', 'N^2t2cKMNfT@a=;f0YfsUi(g~[qoNk?_$&O[U$*1MRl pXopg?md;t!6!4KeG%+6' );
define( 'LOGGED_IN_SALT',   '87o3`]IrQgie<U_sY3!4aQD z(*VafW8C#3zroB[E Y4|mXN}km0m7,hd.h}=Wqy' );
define( 'NONCE_SALT',       'l|l+Q;.czvd0oG^^l>[>:!Wv;ws=]Z9tCqKRUsU`!-+o,4)`Y&~po&.W>d5X6pq0' );

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
$table_prefix = 'www_';

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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
