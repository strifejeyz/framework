<?php

/**
 * This is your project's main configuration file.
 */


/**
 * Define the name for your app here, this will be used for
 * template titles.
 */
define('APP_NAME', 'Strife App');



/**
 * You can use any kind of file type
 * for template files
 */
define('TEMPLATE_TYPE', '.php');



/**
 * Application key will be used to further strengthen
 * password stored on database.
 * generate a new one at least before production
 */
define('APPLICATION_KEY', 'ds9o1xikuloa72olrqggqldj11ka9e9hxusnunow996rpndlyl');



/**
 * You can quickly switch to Maintenance mode.
 */
define('MAINTENANCE_MODE', FALSE);



/**
 * Allow blocking for IPs.
 */
define('IP_BLACKLISTING', FALSE);



/**
 * Directory where to save session files instead of server's
 * default dir
 * just omit if you want to use default dir.
 */
session_save_path('../storage/sessions/');