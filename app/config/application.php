<?php

/**================================================*
 * This is your project's main configuration file. *
 *=================================================*/


/**
 * Define the name for your app here, this will be used for
 * template titles.
 */
const APP_NAME = 'Strife App';


/**
 * Enable cache
 **/
const SMART_CACHE = FALSE;



/**
 * Specify cache expiration in milliseconds
 **/
const CACHE_EXPIRATION = 3600;



/**
 * Here you can define your default Controller
 * class, when you visit '/' this will be called.
 **/
const DEFAULT_CONTROLLER = 'HomeController';


/**
 * For the purpose of redirects
 **/
const BASE_URL = 'http://framework.cc/';


/**
 * Here, you can set the value to
 * automatically cache your pages
 * or to turn it off.
 **/
const CACHED_VIEWS = FALSE;


/**
 * Here you can define your default method
 **/
const DEFAULT_METHOD = 'index';


/**
 * Set whether to display errors or not.
 * refer to https://www.php.net/manual/en/function.error-reporting.php
 **/
const DISPLAY_ERRORS = E_ALL;


/**
 * You can use any kind of file type
 * for template files so instead of
 * render('/auth/login.php')
 * you can render('/auth/login')
 */
const TEMPLATE_TYPE = '.php';


/**
 * Application key will be used to further strengthen
 * password stored on database.
 * generate a new one at least before production
 */
const APPLICATION_KEY = 'yyeg6ba6r736u2vui8ixsk116eki2zfl63bq0insqs4f7qroi2';


/**
 * You can quickly switch to Maintenance mode.
 */
const MAINTENANCE_MODE = FALSE;


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

const CONTROLLERS_PATH  = '/app/controllers/';
const MIGRATIONS_PATH   = '/app/migrations/';
const REQUESTS_PATH     = '/app/requests/';
const SEEDERS_PATH      = '/app/seeders/';
const MODELS_PATH       = '/app/models/';
const CONFIG_PATH       = '/app/config/';
const VIEWS_PATH        = '/app/views/';
const STORAGE_PATH      = '/storage/';
const ASSETS_PATH       = '/assets/';
const VENDOR_PATH       = '/vendor/';
const APP_PATH          = '/app/';