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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'lucas');

/** MySQL database password */
define('DB_PASSWORD', 'rico8827');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ');4O*tEV6u@o|qPQUGeuM,jI:!jbOs/+E:UA#T_V$@yZSI6?*GR@-]u[:PP!gG90');
define('SECURE_AUTH_KEY',  ':k-s [!&GqM6Q|y;hS1IYLly#sPI:=j/E0F:,&,%C:uW-PpO$/Yl5G;7IbG:,aJ8');
define('LOGGED_IN_KEY',    'SX<9-Gcj)pg($<z_1 )p>ueh|]oRs_+x`ZC>hAamQa|KRV;vexHdN4D*SMB8+}=$');
define('NONCE_KEY',        'g`YU]$526#TY?r}(yea}FWpj|f!$P1RFtO1F.6|3($nx6SO>#BH1LkZ.yUG54Y5T');
define('AUTH_SALT',        'E8OOPnb%p:;Yq/8YQ8|jO|*-jLJgcOGmf5D53fE)VtX!Uu^-%nUa]TZLO|D-2@Gm');
define('SECURE_AUTH_SALT', 'MusuQgg2]EBC;+785fzBIgN{/>LVW@#BX: WE|rJPlY|31=CqOf1y+4}a#I;Xjt$');
define('LOGGED_IN_SALT',   '#*^k)ob@@o>q.H]ix:3j,I^+mox;*+.Bpq2/)B6.}@pM)p*Id^-)6/pzP%8+&flw');
define('NONCE_SALT',       'mIjQM> 6.+?Jlb4>%n<Y +{z ,At&TOb5g+UC=!a/qrr@w3flh$whFL cUM%:y5I');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
