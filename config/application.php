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
 * for template files so instead of
 * render('/auth/login.php')
 * you can render('/auth/login')
 */
define('TEMPLATE_TYPE', '.php');



/**
 * Application key will be used to further strengthen
 * password stored on database.
 * generate a new one at least before production
 */
define('APPLICATION_KEY', 'ttatjqoo1osroi5cc5s00xgypmurnvewcr381ypmqe7bi2kmkc');



/**
 * You can quickly switch to Maintenance mode.
 */
define('MAINTENANCE_MODE', FALSE);



/**
 * Allow blocking for selected IPs.
 */
define('IP_BLACKLISTING', FALSE);



/**
 * Directory where to save session files instead of server's
 * default dir
 * just omit if you want to use default dir.
 */
session_save_path('../storage/sessions/');