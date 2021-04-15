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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'stack' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'z7unDM:U1f*;FvOnpfBRI8{3E4ErQY&QLPONuvI]~gBi}BMH%,95%P[[QNZqAiNg' );
define( 'SECURE_AUTH_KEY',  '])AoPJL;x5Iyh!l07-Mx@PR,3A+oT#6,E4&7j=3?BW #q$om[aFi;H<S=UY+flCO' );
define( 'LOGGED_IN_KEY',    '$5Debr6*1x(3#151}|)LoXAz@A.S&zYfp0M`@y]:EW9t@o[8=R/[:ZTYf6)HC=qv' );
define( 'NONCE_KEY',        'RUU3V+/IEeT3l+HK@hhUMO[nu.[*a]70zDQE|Wjc>^q8zwTQ#3 TJQeylSfKC?hV' );
define( 'AUTH_SALT',        'ROx:in<UonE5Nvz!OI,i~C0=b!!z[TH^>gDar?0oc2!L1@qw/,:_mv73ll ~84t>' );
define( 'SECURE_AUTH_SALT', '!q;3XxM->,L7-^yUQ2-b/41w4Cpr:?o3!,9:yB0<u$G0LVmiI%S360Zs/if7t?7P' );
define( 'LOGGED_IN_SALT',   '18g889w=,Rf~nbD)4X9=;${`nJm*)Sva.tp:L13!*?R.7qNler>CpNitNTkl^P)F' );
define( 'NONCE_SALT',       'Ho2Bl5v_K>14()oD8xLA0{k0t=emZ)Yxz7Oti3|/v7R&EAj:,kI}WG$i%N. 1m3_' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

define("FS_METHOD","direct");