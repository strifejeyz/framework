<?php

use Kernel\Engine;

/**
 * Class Route
 */
abstract class Route extends Engine
{
    /**
     * This method will get the route url
     * get('route.name -> /foo/bar')
     * Route::get('route.name.here')
     * output: /foo/bar
     *
     * @param $route
     * @return mixed
     */
    public static function get($route)
    {
        $path = preg_replace('/\:(.*)/', '', self::$routes[$route]['url']);
        $args = func_get_args();

        if (count($args) > 1):
            unset($args[0]);
            foreach ($args as $arg):
                $path .= $arg . "/";
            endforeach;
            $path = rtrim($path, '/');
        endif;

        return ($path);
    }


    /**
     * Redirect to a specific route if
     * it is valid or uses the given string
     * as route.
     *
     * @param $route
     * @var string $location
     */
    public static function redirect($route)
    {
        return header("location: {$route}");
    }


    /**
     * Refresh after the number of seconds of wait
     * to a route given as second parameter
     *
     * @param $seconds
     * @param $route
     * return mixed
     */
    public static function refresh($seconds, $route)
    {
        return header("refresh:{$seconds}; url='{$route}'");
    }
}



