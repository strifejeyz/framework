<?php

/**================================================*
 * This is your project's main configuration file. *
 *=================================================*/


/**
 * Define the name for your app here, this will be used for
 * template titles.
 */
define('APP_NAME', 'Strife App');


/**
 * Here you can define your default Controller
 * class, when you visit '/' this will be called.
 **/
define('DEFAULT_CONTROLLER', 'HomeController');


/**
 * For the purpose of redirects
 **/
define('BASE_URL', 'http://framework/');


/**
 * Here, you can set the value to
 * automatically cache your pages
 * or to turn it off.
 **/
define('CACHED_VIEWS', FALSE);


/**
 * Here you can define your default method
 **/
define('DEFAULT_METHOD', 'index');


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
define('APPLICATION_KEY', 'yyeg6ba6r736u2vui8ixsk116eki2zfl63bq0insqs4f7qroi2');


/**
 * You can quickly switch to Maintenance mode.
 */
define('MAINTENANCE_MODE', FALSE);


/**
 * Directory where to save session files instead of server's
 * default dir
 * just omit if you want to use default dir.
 */
session_save_path('./storage/sessions');


/**
 * Path to directories
 * (Just in case you wanna move things around)
 **/

define('CONTROLLERS_PATH', '/app/controllers/');
define('MIGRATIONS_PATH',  '/app/migrations/');
define('REQUESTS_PATH',    '/app/requests/');
define('SEEDERS_PATH',     '/app/seeders/');
define('MODELS_PATH',      '/app/models/');
define('CONFIG_PATH',      '/app/config/');
define('VIEWS_PATH',       '/app/views/');
define('STORAGE_PATH',     '/storage/');
define('ASSETS_PATH',      '/assets/');
define('VENDOR_PATH',      '/vendor/');
define('APP_PATH',         '/app/');