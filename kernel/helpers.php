<?php

/**
 * Helper Functions
 *
 * set of useful functions for performing
 * simple tasks.
 **/

if (!function_exists('dump')) {
    /**
     * Spit and Stop
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
     * @return string
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
     * @param $filename
     * @param $mime_type
     * @param $preferredFilename
     **/

    function download_file($filename, $mime_type, $preferredFilename = '')
    {
        $returnedName = (strlen($preferredFilename) > 0) ? $preferredFilename : pathinfo($filename, PATHINFO_FILENAME);

        header("Content-type: $mime_type");
        header("Content-disposition:attachment;filename=$returnedName");

        readfile($filename);
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


if (!function_exists('paths')) {
    /**
     * Alternative use of Route::paths()
     * inherited the $routes array from
     * Routes class.
     *
     * @return array
     **/

    function paths()
    {
        return Route::paths();
    }
}


if (!function_exists('path')) {
    /**
     * Returns last URL processed.
     *
     * @return array
     **/
    function path()
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
     * Alternative to Route::redirect()
     *
     * @param $interval
     * @param $route
     * @return string
     **/

    function refresh($interval, $route)
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


if (!function_exists('extension')) {
    /**
     * Returns an extension of a
     * given string variable
     *
     * @param $string_var
     * @return string
     **/

    function extension($string_var)
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
     */
    function date_difference($date1, $date2)
    {
        $date_entry = new \DateTime($date1);
        $dateNow = new \DateTime($date2);

        return $dateNow->diff($date_entry)->format("%a");
    }
}


if (!function_exists('errors')) {
    /**
     * Return the errors session
     * values
     *
     * @param $errorName
     * @return string
     */
    function errors($errorName)
    {
        if (isset($_SESSION['__ERRORS__'][$errorName])) {
            return ($_SESSION['__ERRORS__'][$errorName]);
        } else {
            return (null);
        }
    }
}


if (!function_exists('fields')) {
    /**
     * Return the last form values returned
     * from request class
     *
     * @param $name
     * @return string
     */
    function fields($name)
    {
        if (isset($_SESSION['__FIELDS__'][$name])) {
            return ($_SESSION['__FIELDS__'][$name]);
        } else {
            return (null);
        }
    }
}


if (!function_exists('date_now')) {
    /**
     * Return current date
     *
     * @param $separator
     * @return string
     */
    function date_now($separator = ' ')
    {
        return Date("F{$separator}j,{$separator}Y");
    }
}


if (!function_exists('time_now')) {
    /**
     * Return current time
     *
     * @return string
     */
    function time_now()
    {
        return Date('h:i A');
    }
}


if (!function_exists('year_now')) {
    /**
     * Return current year
     *
     * @return string
     */
    function year_now()
    {
        return Date('Y');
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
        include("../views/errors/{$n}.php");
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

if (!function_exists('assign')) {

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
    function assign($url, $action, $requestMethod = null, $namespace = null)
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
     * @param string $requestMethod
     * @param null $namespace
     * @return mixed
     */
    function post($url, $action, $requestMethod = 'POST', $namespace = null)
    {
        return Route::post($url, $action, $requestMethod, $namespace);
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

if (!function_exists('root_dir')) {
    /**
     * @return mixed
     */
    function root_dir()
    {
        return str_replace('public', '', $_SERVER['DOCUMENT_ROOT']);
    }
}


if (!function_exists('app_dir')) {
    /**
     * @return string
     */
    function app_dir()
    {
        return str_replace('public', '', $_SERVER['DOCUMENT_ROOT']) . "app" . DIRECTORY_SEPARATOR;
    }
}


if (!function_exists('config_dir')) {
    /**
     * @return string
     */
    function config_dir()
    {
        return str_replace('public', '', $_SERVER['DOCUMENT_ROOT']) . "config" . DIRECTORY_SEPARATOR;
    }
}


if (!function_exists('kernel_dir')) {
    /**
     * @return string
     */
    function kernel_dir()
    {
        return str_replace('public', '', $_SERVER['DOCUMENT_ROOT']) . "kernel" . DIRECTORY_SEPARATOR;
    }
}


if (!function_exists('public_dir')) {
    /**
     * @return mixed
     */
    function public_dir()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }
}


if (!function_exists('storage_dir')) {
    /**
     * @return string
     */
    function storage_dir()
    {
        return str_replace('public', '', $_SERVER['DOCUMENT_ROOT']) . "storage" . DIRECTORY_SEPARATOR;
    }
}


if (!function_exists('vendor_dir')) {
    /**
     * @return string
     */
    function vendor_dir()
    {
        return str_replace('public', '', $_SERVER['DOCUMENT_ROOT']) . "vendor" . DIRECTORY_SEPARATOR;
    }
}


if (!function_exists('views_dir')) {
    /**
     * @return string
     */
    function views_dir()
    {
        return str_replace('public', '', $_SERVER['DOCUMENT_ROOT']) . "views" . DIRECTORY_SEPARATOR;
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