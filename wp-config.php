<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'matthhf3_wor1');

/** MySQL database username */
define('DB_USER', 'matthhf3_wor1');

/** MySQL database password */
define('DB_PASSWORD', '30aQBAsT');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '~?v~n0.%9ON9#|-c*-/nqTzT~mDi`+C1owT ip+LDfOu6OvATwm`ybV=Heq!Re7o');
define('SECURE_AUTH_KEY',  'k=:tLIoHI62vI|]5<q-Y`gE+WaOV3T-g84b-kP)8+1qcW5kcg}7:]cwr/%>2)C{~');
define('LOGGED_IN_KEY',    'k% 0H]~XJ`;$|TR+~V,BQy.sH/g.Q9j$ ++:#RiN!9vr!_+C-uwsbokY#4+WR/G|');
define('NONCE_KEY',        '!f/(1|ad|m9}es-^emd)/!?yd+T_?>|-q=9KGe_TC|n^ywW!Q6l?R9qpK/r zO7q');
define('AUTH_SALT',        'K|)&csb#Vso=tSisiP5P{Ps#)eReVcC[N}`f#lthFgxCp(Tli:?4ae>mMU3mTAc)');
define('SECURE_AUTH_SALT', 'e8ay=4BC5w-mx#)g^}+MPXyfF]D}v=6+J?kGo2@X |?P>{B[n-|TlmGw^=J+vq-G');
define('LOGGED_IN_SALT',   '5~h+|+?{r;-<s#s7b(Pm^qEU ~XNt%p(G){e:Z=W$_sW7Yfu28wT!|5%eVR<^0!1');
define('NONCE_SALT',       '|OleH)blNB`Av?9b%:*N8trA{Hj]jg{!EY4CJ-jE|urH#p_}oKm?$XL6R8[-,+KG');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'iug_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
