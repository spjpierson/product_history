<?php
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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'product_history_site' );

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
define( 'AUTH_KEY',         'GIgeYxI7i~i|aO#FzTcR.Pk7%!REF+K6>2w/$J/9[87J4[4gXz4$$K@K~G,cW)|[' );
define( 'SECURE_AUTH_KEY',  'wU$F]w[0i,0B<CCrUyP^|a_ be762thGm9xc%VZuY6I~xAa$FxZpsVcwX7Sx@6T@' );
define( 'LOGGED_IN_KEY',    'zo!cCL_sJ%`m_Rd]Z#0gN`OmMNr{|&Zp/>7DlRy9pdO[{9!esU$KK`5O},OC,9He' );
define( 'NONCE_KEY',        'h&IDZ@gnhxoz;sfzH{WJ tG0v:<Y3$7MWY]x+uFOpqmKjP1:BPh3q_V~^wis+R:W' );
define( 'AUTH_SALT',        'P1alLw^/+Rpu/Kw]X}vdd7C%8%|f%JzhS;8/?j)c3tYck:TuL6>acq i9$m8Dc?m' );
define( 'SECURE_AUTH_SALT', '+J]H2:S94CXWNB4c/3/usmKH8s(Uk;N/y9Q`6k^O[9kC0]ehb+6b25Yk{sdX+ST=' );
define( 'LOGGED_IN_SALT',   '_O y,A75cc[2.QMREZ&eMSlbBf@FcT{LjDi3^-kiiX17S8@4ebRHiC;:6neJAa*k' );
define( 'NONCE_SALT',       '<T>=MK@,xeaa`]]_*I/uOA8OO+^6s$Tw# x{91Yz3%.kQdD/P[(+FOTOA]&~,zv=' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
