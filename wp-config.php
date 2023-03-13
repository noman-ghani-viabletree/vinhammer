<?php
define( 'WP_CACHE', true ); // Added by WP Rocket

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
define( 'DB_NAME', 'vinhammer' );

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
define( 'AUTH_KEY',         '3G,%)_U[??83~z!a3.%g/ys]`a(7aP/a)[$6Jxiv0b&@1sbI+{T3RR%@O-p>I:/-' );
define( 'SECURE_AUTH_KEY',  'Z~ aDv[LM! gn{AXlfb*Ut&zHC-JI%k<I;u=~1sO?7 w6MM6i/x/84lbOBz1!h,#' );
define( 'LOGGED_IN_KEY',    '8@WHm33H*8:Jgf8xW)nX?^YCj9hk]_i)YC27E00EFVDXFmfE),.3spN?@<f_mWU%' );
define( 'NONCE_KEY',        'c{j&BE<s,S;!Q).WU@qO<E@2?k(/BWD$n.Z1}H_p[lX:5x2vR.h|8h_DKLr_|cb$' );
define( 'AUTH_SALT',        'o <~a3q[P*H mPrwuh/C);]N>. ?_7(i X- (_7>9k6$dLv>Qt7//X,2Nv>``ltI' );
define( 'SECURE_AUTH_SALT', ':h[[of@.V}G<qG-{d>?zDh<R%&3R$+ukLe}YbE8z[&Xw+g24<W.3{OmP 57%ws=?' );
define( 'LOGGED_IN_SALT',   'M/F3;G?Un3y!E{J6IRNA(?S&]#,Yv3?PcQmzFng.TfUtdY>A(%[Qw]IC1Ue?(^Xq' );
define( 'NONCE_SALT',       'ifK[g>Ts7gjGfgd%N:[CP[m%D>S(B!]U=bs_E60<?}.KlF+MP  H`mcL$+bJ(J*{' );

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
define('FS_METHOD', 'direct');
/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
