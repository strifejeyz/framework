<?php namespace Kernel;


/**
 * Setting and Dispatching routes
 * Class Engine
 * Created 10/23/2015
 * Updated 03/27/2017
 *
 * @package Kernel\Engine
 */
class Engine
{
    /**
     * holds the routes assigned to get() method
     *
     * @var array
     */
    public static $routes = array();

    /**
     * If false, strict mode is off for URL matching
     * means /home is equal to /HOME
     *
     * @var bool
     */
    private static $strict = false;

    /**
     * default controller
     *
     * @var string
     */
    private static $controller = "HomeController";
    /**
     * default method
     *
     * @var string
     */
    private static $method = "index";
    /**
     * container for parameters.
     *
     * @var array
     */
    private static $parameters = array();
    /**
     * in case of controllers in subdirectories
     *
     * @var string
     */
    private static $subdirectory = null;
    /**
     * in case controllers are using namespace
     *
     * @var string
     */
    private static $requestMethod = null;
    /**
     * in case controllers are using namespace
     *
     * @var string
     */
    private static $namespace = null;


    /**
     * Parse and match the URL to each assigned routes and calls
     * the assigned controller and it's method along with the
     * parameters based on array given in the get() method.
     *
     * @var array $originalUrl
     * @var int $compare
     * @var array $path
     * @var array $url
     * @var array $params
     * @return mixed
     */
    public function __construct()
    {
        session_start();

        if (MAINTENANCE_MODE == TRUE) {
            return self::error(503);
        }

        if (IP_BLACKLISTING == TRUE) {
            self::blacklist();
        }

        $originalUrl = self::parseUrl();
        $compare = 0;

        if (empty($originalUrl)) {
            return self::dispatch();
        } else {
            foreach (array_values(self::$routes) as $route) {
                $path = explode('/', trim($route['url'], '/'));
                $route['params'] = empty($route['params']) ? 0 : $route['params'];

                if (count($path) === count($originalUrl)) {
                    $path = array_slice($path, 0, count($path) - $route['params']);
                    $url = array_slice($originalUrl, 0, count($originalUrl) - $route['params']);

                    foreach ($path as $index => $u) {
                        if (self::$strict == true) {
                            if ($u === $url[$index]) {
                                $compare++;
                                continue;
                            } else {
                                $compare = 0;
                                break;
                            }
                        } else {
                            $key = preg_replace('/\[/', '\[', $url[$index]);
                            $key = preg_replace('/\]/', '\]', $key);
                            $key = preg_replace('/\(/', '\(', $key);
                            $key = preg_replace('/\)/', '\)', $key);
                            if (preg_match("/^{$key}$/i", $u)) {
                                $compare++;
                                continue;
                            } else {
                                $compare = 0;
                                break;
                            }
                        }
                    }

                    if ($compare === count($url)) {
                        $params = array_slice($originalUrl, count($originalUrl) - $route['params']);

                        if (isset($route['closure'])) {
                            if (!empty($route['requestMethod'])) {
                                if (strtoupper($route['requestMethod']) !== $_SERVER['REQUEST_METHOD']) {
                                    return self::error(404);
                                }
                            }
                            return call_user_func_array($route['closure'], $params);
                        }

                        self::$controller = $route['controller'];
                        self::$method = $route['method'];
                        self::$parameters = $params;
                        self::$subdirectory = $route['subdirectory'];
                        self::$namespace = $route['namespace'];
                        self::$requestMethod = $route['requestMethod'];

                        return self::dispatch();
                    }
                } else {
                    continue;
                }
            }

            return self::error(404);
        }
    }


    /**
     * Parse the URL if present.
     *
     * @return array
     */
    private static function parseUrl()
    {
        if (isset($_GET['url'])) {
            $url = filter_var($_GET['url'], FILTER_SANITIZE_URL);
            $url = explode('/', trim($url, '/'));
        } else {
            $url = array();
        }

        return ($url);
    }


    /**
     * assign routes paths, names, controllers, etc.
     * dispatch() method relies on it.
     * Note: if route name is not specified in the 1st argument,
     * we'll use the url as the route name instead.
     *
     * @param string $url
     * @param string $action
     * @param string $requestMethod
     * @var string $path
     * @var string $sub
     * @var array $data
     * @var mixed $name
     * @var int $param
     * @return bool
     */
    public static function assign($url, $action, $requestMethod = null, $namespace = null)
    {
        if (is_array($url)) {
            $name = array_keys($url)[0];
            $url = array_values($url)[0];
        } elseif (preg_match('/(.*)\-\>\>(.*)/', $url)) {
            $data = explode('->>', str_replace(' ', '', $url));
            $name = $data[0];
            $url = $data[1];
        } else {
            $name = $url;
        }

        self::$routes[$name]['url'] = $url;

        if (is_null($requestMethod)) {
            self::$routes[$name]['requestMethod'] = "";
        } else {
            self::$routes[$name]['requestMethod'] = $requestMethod;
        }

        if (is_null($namespace)) {
            self::$routes[$name]['namespace'] = "";
        } else {
            self::$routes[$name]['namespace'] = $namespace;
        }

        $params = 0;
        for ($i = 0; $i < strlen($url); $i++) {
            if ($url[$i] == ":") {
                $params++;
            }
        }

        if (empty($params)) {
            self::$routes[$name]['params'] = 0;
        } else {
            self::$routes[$name]['params'] = $params;
        }

        if (is_callable($action)) {
            return self::$routes[$name]['closure'] = $action;
        }

        if (!preg_match('/\:\:/', $action)) {
            return trigger_error("Argument passed via assign() doesn't have a valid separator '::'", E_USER_ERROR);
        }

        $path = explode('/', trim($action, '/'));

        self::$routes[$name]['controller'] = explode('::', $path[count($path) - 1])[0];
        self::$routes[$name]['method'] = explode('::', trim($path[count($path) - 1], '()'))[1];

        if (preg_match('/\//', $action)) {
            $sub = "";
            for ($i = 0; $i < count($path) - 1; $i++) {
                $sub = $sub . $path[$i] . '/';
            }
            self::$routes[$name]['subdirectory'] = trim($sub, '/') . '/';
        } else {
            self::$routes[$name]['subdirectory'] = "";
        }

        return (true);
    }


    /**
     * Instantiate the controller and call the method along
     * with the parameters if present.
     *
     * @return mixed
     */
    private static function dispatch()
    {
        if (!empty(self::$requestMethod)) {
            if (strtoupper(self::$requestMethod) !== $_SERVER['REQUEST_METHOD']) {
                return self::error(404);
            }
        }

        require_once '../app/controllers/' . self::$subdirectory . self::$controller . '.php';
        $controller = self::$namespace . self::$controller;

        return (call_user_func_array([new $controller(), self::$method], self::$parameters));
    }


    /**
     * Replication of assign() method to
     * set route to accept post request only
     *
     * @param string $url
     * @param string $action
     * @param string $requestMethod
     * @param string $namespace
     * @return mixed
     */
    public static function post($url, $action, $requestMethod = 'POST', $namespace = null)
    {
        return self::assign($url, $action, $requestMethod, $namespace);
    }


    /**
     * Return array containing all the routes and can be
     * dumped to view those values.
     *
     * @return array
     */
    public static function paths()
    {
        return (self::$routes);
    }


    /**
     * If non of the routes matched on the URL, return a 404 page.
     *
     * @param $n
     * @return mixed
     */
    private static function error($n)
    {
        return page_error($n);
    }


    /**
     * Block IPs listed on .blacklist file.
     *
     * @return mixed
     */
    private static function blacklist()
    {
        $ips = file_contents_to_array('../.blacklist');

        foreach ($ips as $ip) {
            if (remote_ip() == $ip) {
                return self::error(403);
            } else {
                continue;
            }
        }
    }


    /**
     * Set whether strict URL matching on character casing
     * should be performed
     *
     * @param $bool
     * @return bool
     */
    public function strict($bool)
    {
        if ($bool == true) {
            self::$strict = true;
        } else {
            self::$strict = false;
        }
        return (true);
    }
}