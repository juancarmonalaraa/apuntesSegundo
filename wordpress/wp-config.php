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
define( 'AUTH_KEY',         '<eaO7t9AE3h^9^6Faz^#G{ 54Hr{In<@(W*AP*^_an3oEbK,CL3hyN/Z0mSyKlVb' );
define( 'SECURE_AUTH_KEY',  'B_h_:u?6a f`^7qf#oKPnt|;u8]tORMT;G34m]*yfE_`|s)G;c{Kf}L}#84tbi.4' );
define( 'LOGGED_IN_KEY',    'f)X/xn3WsYanw,>[vdz`}QA}{3R![GlgJN>>9gI_Vd9r}&r#Bc5/ka9=(}C1_?;8' );
define( 'NONCE_KEY',        '=rSA=#^7Qky_l Hz~-cbi% {{zO)#2s}MUWIv+~@5+sGi>.sykn*;{.4%~5UlT~?' );
define( 'AUTH_SALT',        'PzfpZ3LdTV+Az}1E8bF(GQ**nC,g).`$a2<pW</Pms%x}|(^6m6Z%@!5rbP ihd?' );
define( 'SECURE_AUTH_SALT', 'o7KS].BIE-*Xt,vllID.qG5&U):BeJFCVgMp8Rl.9@)K03<^:Oc/plh-pRJScQ9W' );
define( 'LOGGED_IN_SALT',   'Lq?l#?W5<Zcecp+l<U.OY]P%O+[$fcNDiqobCn?omcSE:48N4G|Wv>jW8ebu(K;T' );
define( 'NONCE_SALT',       'Xw|1|@*TnL=}NOm&A?2a(Vy(>WBnn)-EzBxGrkDFNmr rL7 5[PzSsb6_QRyyoWZ' );

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
