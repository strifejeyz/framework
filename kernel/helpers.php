<?php

/**=============================================*
 *              Helper Functions                *
 *===============================================
 * set of useful functions for performing
 * simple tasks.
 **/





if (!function_exists('dump')) {
    /**
     * Data dump
     *
     * @param $variable
     * @param bool $die
     **/
    function dump($variable, $die = true)
    {
        echo '<code><pre>' . var_export($variable, TRUE) . '</pre></code>';

        if ($die) {
            die();
        }
    }
}


if (!function_exists('to_megabytes')) {
    /**
     * Convert bytes to megabytes
     *
     * @param $bytes
     * @param int $structure
     * @return string|null
     */
    function to_megabytes($bytes, $structure = 3)
    {
        return bcdiv($bytes, 1048576, $structure);
    }
}


if (!function_exists('random_string')) {
    /**
     * Returns a randomized pseudo
     * string value.
     *
     * @param $length
     * @return string
     **/
    function random_string($length = 50)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $string = "";

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, (strlen($characters) - 1))];
        }

        return $string;
    }
}


if (!function_exists('csv_encode')) {
    /**
     * encode an array to csv values
     *
     * @param array $values
     * @return string
     */
    function csv_encode($values = [])
    {
        $csv = "";
        foreach ((array)$values as $value) {
            $csv .= "$value,";
        }

        return (trim($csv, ','));
    }
}


if (!function_exists('csv_decode')) {
    /**
     * encode an array to csv values
     *
     * @param array $values
     * @return array
     */
    function csv_decode($values)
    {
        $values = explode(',', trim($values, ','));
        return ($values);
    }
}


if (!function_exists('download_file')) {
    /**
     * Forces a file to be downloaded
     * and not be open by default.
     *
     * @param $file
     * @param null $preferredName
     * @return bool
     */
    function download_file($file, $preferredName = null)
    {
        if (!is_null($preferredName)) {
            $filename = $preferredName;
        } else {
            $filename = $file;
        }

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        } else {
            return false;
        }
    }
}


if (!function_exists('file_contents_to_array')) {
    /**
     * Reads a text file and assigns every
     * line to an array
     *
     * @param $filename
     * @return array
     * */

    function file_contents_to_array($filename)
    {
        $file = fopen($filename, "r");
        $values = [];
        $counter = 0;

        while (!feof($file)) {
            $values[$counter] = fgets($file);
            $counter++;
        }

        return $values;
    }
}


if (!function_exists('route')) {
    /**
     * Alternative use of Route::get()
     * inherited the $routes array from
     * Routes class.
     *
     * @param $route_name
     * @return string
     **/

    function route($route_name)
    {
        return call_user_func_array(['Route', 'get'], func_get_args());
    }
}


if (!function_exists('route_paths')) {
    /**
     * Alternative use of Route::paths()
     * inherited the $routes array from
     * Routes class.
     *
     * @return array
     **/

    function route_paths()
    {
        return Route::paths();
    }
}


if (!function_exists('uri')) {
    /**
     * Returns last URL processed.
     *
     * @return array
     **/
    function uri()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            return ($_SERVER['REQUEST_URI']);
        } else {
            return (null);
        }
    }
}


if (!function_exists('intended')) {
    /**
     * Returns last URL processed.
     *
     * @return array
     **/

    function intended()
    {
        if (isset($_SESSION['__INTENDED__'])) {
            return ($_SESSION['__INTENDED__']);
        } else {
            return (null);
        }
    }
}

if (!function_exists('html')) {
    /**
     * Returns escaped tags.
     *
     * @param $string
     * @return string
     */
    function html($string)
    {
        return htmlspecialchars($string);
    }
}


if (!function_exists('redirect')) {
    /**
     * Alternative to Route::redirect()
     *
     * @param $route
     * @return string
     **/
    function redirect($route)
    {
        return Route::redirect($route);
    }
}


if (!function_exists('refresh')) {
    /**
     * Alternative to Route::refresh()
     *
     * @param $interval
     * @param $route
     * @return string
     **/

    function refresh($route, $interval)
    {
        return Route::refresh($interval, $route);
    }
}


if (!function_exists('render')) {
    /**
     * Alternative use of View::render()
     *
     * @param $template
     * @param $params
     * @return string
     **/

    function render($template, $params = [])
    {
        return View::render($template, $params);
    }
}


if (!function_exists('extend')) {
    /**
     * Extend or include a layout by giving the
     * layout folder name as first parameter.
     *
     * @param $template
     * @param $params
     * @return string
     **/

    function extend($template, $params = [])
    {
        return View::extend($template, $params);
    }
}


if (!function_exists('stop')) {
    /**
     * End an extended layout
     *
     * @param $params
     * @return string
     **/

    function stop($params = [])
    {
        return View::stop($params);
    }
}


if (!function_exists('parse')) {
    /**
     * Include a file and render content
     *
     * @param $template
     * @return string
     **/

    function parse($template)
    {
        return View::parse($template);
    }
}


if (!function_exists('filename')) {
    /**
     * Returns a string(without extension)
     * of a given string variable
     *
     * @param $string_var
     * @return string
     **/

    function filename($string_var)
    {
        return pathinfo($string_var, PATHINFO_FILENAME);
    }
}


if (!function_exists('file_extension')) {
    /**
     * Returns an extension of a
     * given string variable
     *
     * @param $string_var
     * @return string
     **/

    function file_extension($string_var)
    {
        return pathinfo($string_var, PATHINFO_EXTENSION);
    }
}


if (!function_exists('css')) {
    /**
     * Shortcut to adding css files
     * multiple arguments are handled recursively
     *
     * @param $path
     * @return string
     **/

    function css($path)
    {
        $styles = "";
        if (count(func_get_args()) > 1) {
            foreach (func_get_args() as $arg) {
                $styles .= "<link href=\"$arg\" rel=\"stylesheet\" type=\"text/css\">\n";
            }
        } else {
            $styles = "<link href=\"$path\" rel=\"stylesheet\" type=\"text/css\">\n";
        }

        return ($styles);
    }
}


if (!function_exists('js')) {
    /**
     * Shortcut to adding JS files
     * multiple arguments are handled recursively
     *
     * @param $path
     * @return string
     **/

    function js($path)
    {
        $scripts = "";
        if (count(func_get_args()) > 1) {
            foreach (func_get_args() as $arg) {
                $scripts .= "<script type=\"text/javascript\" src=\"$arg\"></script>\n";
            }
        } else {
            $scripts = "<script type=\"text/javascript\" src=\"$path\"></script>\n";
        }
        return ($scripts);
    }
}


if (!function_exists('date_difference')) {
    /**
     * Compares two days and returns
     * number of days.
     *
     * @param $date1
     * @param $date2
     * @return string
     * @throws Exception
     */
    function date_difference($date1, $date2)
    {
        $date_entry = new DateTime($date1);
        $dateNow = new DateTime($date2);

        return $dateNow->diff($date_entry)->format("%a");
    }
}


if (!function_exists('form_error')) {
    /**
     * Return the errors session
     * values
     *
     * @param $field
     * @return string
     */
    function form_error($field)
    {
        if (isset($_SESSION['__ERRORS__'][$field])) {
            return ($_SESSION['__ERRORS__'][$field]);
        } else {
            return (null);
        }
    }
}


if (!function_exists('set_form_error')) {
    /**
     * Set a form error
     *
     * @param $name
     * @param $value
     * @return string
     */
    function set_form_error($name, $value)
    {
        $_SESSION['__ERRORS__'][$name] = $value;
    }
}


if (!function_exists('form_values')) {
    /**
     * Return the last form values returned
     * from request class
     *
     * @param $name
     * @return string
     */
    function form_values($name)
    {
        if (isset($_SESSION['__FIELDS__'][$name])) {
            return ($_SESSION['__FIELDS__'][$name]);
        } else {
            return (null);
        }
    }
}


if (!function_exists('remote_ip')) {
    /**
     * Return current client IP
     *
     * @return string
     */
    function remote_ip()
    {
        $ip = null;

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = null;
        }

        return ($ip);
    }
}


if (!function_exists('remote_port')) {
    /**
     * Return remote port
     *
     * @return string
     */
    function remote_port()
    {
        return $_SERVER['REMOTE_PORT'];
    }
}


if (!function_exists('page_error')) {
    /**
     * Custom error pages
     *
     * @param $n
     * @return string
     */
    function page_error($n)
    {
        include("." . VIEWS_PATH . "errors/{$n}.php");
        return exit();
    }
}


use Kernel\Security\Hash;

if (!function_exists('hash_encode')) {

    /**
     * Hash the string
     *
     * @param $string
     * @return mixed
     */
    function hash_encode($string)
    {
        return Hash::encode($string);
    }
}


if (!function_exists('hash_verify')) {

    /**
     * Compare string to hashed string
     *
     * @param $string
     * @param $hashed
     * @return mixed
     */
    function hash_verify($string, $hashed)
    {
        return Hash::verify($string, $hashed);
    }
}

if (!function_exists('get')) {

    /**
     * Assign a route config.
     * same as Route::assign() method.
     *
     * @param string $url
     * @param string $action
     * @param null $requestMethod
     * @param null $namespace
     * @return mixed
     */
    function get($url, $action, $requestMethod = null, $namespace = null)
    {
        return Route::assign($url, $action, $requestMethod, $namespace);
    }
}

if (!function_exists('post')) {

    /**
     * Assign a POST request route
     * that accepts POST request method.
     *
     * @param string $url
     * @param string $action
     * @param string $request_method
     * @param null $namespace
     * @return mixed
     */
    function post($url, $action, $request_method = 'POST', $namespace = null)
    {
        return Route::post($url, $action, $request_method, $namespace);
    }
}

if (!function_exists('setflash')) {

    /**
     * Create a session flash message
     *
     * @param string $name
     * @param string $message
     * @return mixed
     */
    function setflash($name, $message)
    {
        return Session::setFlash($name, $message);
    }
}

if (!function_exists('getflash')) {

    /**
     * Get and destroy a flash message
     *
     * @param string $name
     * @return mixed
     */
    function getflash($name)
    {
        return Session::getFlash($name);
    }
}

if (!function_exists('flash_exists')) {

    /**
     * Get and destroy a flash message
     *
     * @param string $name
     * @return mixed
     */
    function flash_exists($name)
    {
        return Session::flashExists($name);
    }
}

if (!function_exists('hostname')) {

    /**
     * Return current host's name
     *
     * @return mixed
     */
    function hostname()
    {
        return $_SERVER['HTTP_HOST'];
    }
}

if (!function_exists('referer')) {

    /**
     * Return current referer name
     *
     * @return mixed
     */
    function referer()
    {
        return $_SERVER['HTTP_REFERER'];
    }
}

/**
 * Application Directories
 */

if (!function_exists('root_path')) {
    /**
     * @return mixed
     */
    function root_path()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }
}


if (!function_exists('assets_path')) {
    /**
     * @return string
     */
    function assets_path()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ltrim(ASSETS_PATH, '/');
    }
}

if (!function_exists('app_path')) {
    /**
     * @return string
     */
    function app_path()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ltrim(APP_PATH, '/');
    }
}


if (!function_exists('config_path')) {
    /**
     * @return string
     */
    function config_path()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ltrim(CONFIG_PATH, '/');
    }
}


if (!function_exists('kernel_path')) {
    /**
     * @return string
     */
    function kernel_path()
    {
        return $_SERVER['DOCUMENT_ROOT'] . "kernel";
    }
}


if (!function_exists('storage_path')) {
    /**
     * @return string
     */
    function storage_path()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ltrim(STORAGE_PATH, '/');
    }
}


if (!function_exists('vendor_path')) {
    /**
     * @return string
     */
    function vendor_path()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ltrim(VENDOR_PATH, '/');
    }
}


if (!function_exists('views_path')) {
    /**
     * @return string
     */
    function views_path()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ltrim(VIEWS_PATH, '/');
    }
}


if (!function_exists('controllers_path')) {
    /**
     * @return string
     */
    function controllers_path()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ltrim(CONTROLLERS_PATH, '/');
    }
}


if (!function_exists('requests_path')) {
    /**
     * @return string
     */
    function requests_path()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ltrim(REQUESTS_PATH, '/');
    }
}


if (!function_exists('seeders_path')) {
    /**
     * @return string
     */
    function seeders_path()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ltrim(SEEDERS_PATH, '/');
    }
}


if (!function_exists('models_path')) {
    /**
     * @return string
     */
    function models_path()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ltrim(MODELS_PATH, '/');
    }
}


if (!function_exists('migrations_path')) {
    /**
     * @return string
     */
    function migrations_path()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ltrim(MIGRATIONS_PATH, '/');
    }
}


if (!function_exists('endswith')) {
    /**
     * Determines whether an ending
     * characters of a string matches
     * w/ the given parameter.
     *
     * @param $string
     * @param $search
     * @param $matchCasing
     * @return string
     */
    function endswith($string, $search, $matchCasing = false)
    {
        $case = '';
        if ($matchCasing == false) {
            $case = 'i';
        }

        if (preg_match('/' . $search . '$/' . $case, $string)) {
            return (true);
        } else {
            return (false);
        }
    }
}


if (!function_exists('startswith')) {
    /**
     * Determines whether starting
     * characters of a string matches
     * w/ the given parameter.
     *
     * @param $string
     * @param $search
     * @param $matchCasing
     * @return string
     */
    function startswith($string, $search, $matchCasing = false)
    {
        $case = '';
        if ($matchCasing == false) {
            $case = 'i';
        }

        if (preg_match('/^' . $search . '/' . $case, $string)) {
            return (true);
        } else {
            return (false);
        }
    }
}