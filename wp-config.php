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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'uelsocial' );

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
define( 'AUTH_KEY',         'L{Q{+%*2~+.KW8Ld5V@[.6[VL)YlhM)0tFb 0N1J8kzg q#+k1Be[DqVR&+B/E[D' );
define( 'SECURE_AUTH_KEY',  '`ow[.8/Vno0Za9r1|uf8R SV>>ae>4u>N**Z[c*2/5nuP;#6!LFkG#<{RRhT}%?f' );
define( 'LOGGED_IN_KEY',    'nh&K4b(Tl{vA)3,Qofn;=1.Blso$.46!oZW.dj(Cc<<fd;9=Yn)>~{Pisg)Y?D<0' );
define( 'NONCE_KEY',        'f{u7V9k7DxuZ{dO]JXMQl@ch=fQ#*T@j}6#3jP|{mTOpz<&o7kpV%iOn_eU>8H7I' );
define( 'AUTH_SALT',        'LZ0;G}1(<$y#?59n 03z :>g$if=cZVZP_<BI-<gl;MPg1^b~t0YLVxvRvrw8VZ3' );
define( 'SECURE_AUTH_SALT', 'DH1iF+5yFR{)y2bu;pA<o(QnwPJCtk{a=:c*@Dw+j+,D7Yq@0n=N%6Q5$?mCYp}{' );
define( 'LOGGED_IN_SALT',   '83mvY(;Y#]Kq^Y>WU24=i07uXRW;+CUWP)We;Gr48?WT}W})S&nihP3lZ.SR]T1~' );
define( 'NONCE_SALT',       'UV(o}!d^`(K6s5Rps:LpHEtfaOVz&f?.K).KY~qd(O?.TU`/wcWW02BH^]Ms&W=N' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
