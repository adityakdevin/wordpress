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
define( 'DB_NAME', 'wordpress' );

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
define( 'AUTH_KEY',         '0!ZOoV@o8]ks(wSAnf~j7gyh!;L1S#ydWCa0DnTi S]>q~2W1A4Se53T2cfbjfmb' );
define( 'SECURE_AUTH_KEY',  '0Pz53Umjx5IH:R(nDIRVA,woWBFX`BJ)CR^0|4%jV1LAdWEQ#a;-QQ(IgKPUEN:$' );
define( 'LOGGED_IN_KEY',    '1o,(k<(L3f(o_G#.7_aWr) h&6K9Zr|VxxPP_zf<qjqYxhD(PWW2nr}OT>8+&agV' );
define( 'NONCE_KEY',        'Hxjx+G7!&*Z*ly4HG/YGP8S|p+}b=#]q15FiF)J6[N=,@h6)iDeJ)N 54z&^A-Q}' );
define( 'AUTH_SALT',        '0+ j`uNOC<&SP/#:*bB{CO]xCt#_8hP/-MpY*r9>dnS%Kp7teZEz>mQ>zUobo_J@' );
define( 'SECURE_AUTH_SALT', 'tZ-k^Mqin^:`bnm1#wqtkR6d8Kg97TLuwhYa,TkiGfKKee9!eEidd2[2Jo!GUF1*' );
define( 'LOGGED_IN_SALT',   'Tv|!rwp+,`(cE 3vzHbEDo:~m(c0B-9!Dq*jVwB<a6 ix4OW1$4k{|?`}pPQ:l7n' );
define( 'NONCE_SALT',       ']wRml|+NigFX*.Q{Fm!%] vgn{6>Xf2}II#,SpSYVI8izmylI;15yKVC}lRo7}f-' );

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
