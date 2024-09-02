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
define('DB_NAME', 'kodaimalai');

/** Database username */
define('DB_USER', 'root');

/** Database password */
define('DB_PASSWORD', 'root');

/** Database hostname */
define('DB_HOST', 'localhost');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

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
define('AUTH_KEY', 'pX+3RkA6liVt6f5wCGJce8RcmpZC0+DitmdVlx2uQ8H6vlwoh1Mg4NY0PkwDbKWp');
define('SECURE_AUTH_KEY', 'PRkLE0NDfmVYqp4iwN8xRPUE85HCmXmUZe5GRzeVj+CvGb8Uu7aSgADdZU0Ktxv1');
define('LOGGED_IN_KEY', '7gcCAeRLYsJJZQB3l8ajNmrmlFkn08rB7nbh4EinTSWCuH40/wvJi/yKQLsV44Co');
define('NONCE_KEY', 'x/cix4yiu7nnZdy1jFX77l2zaeirN/f2KYCAzXA3D4IDnuAFFwaMjWVq5q7qxXGW');
define('AUTH_SALT', 'yiE7xccPxQ/PCJMKh18tpmZvHq1GUdEYVz4a2Iyy0qu0nHvKRnJ56eRG7Pv4M4Ps');
define('SECURE_AUTH_SALT', '4khQ28f5Iw+piTzzJpL3irWYOvmQeei7VlHf4RTcf9rpQvi7tY8IPH454GfelyDD');
define('LOGGED_IN_SALT', 'ps6/9NC3qCVTHvObeZP0qhwwVtC803Oj9VW6WNK9S9ERivh4QQGB+U2hFFZh2xo9');
define('NONCE_SALT', 'zx8gNylWy47pRR7AVU/qVjv9RDy8xcquPCZ3ptSIPcywqt5/OIDhL6mQ+suvgJaK');

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
* @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
*/
define( 'WP_DEBUG', false );
define('FS_METHOD','direct');
/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
