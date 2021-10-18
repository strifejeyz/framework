<?php /** @noinspection ALL */

namespace Kernel;


/**
 * Setting and Dispatching routes
 * Class Engine
 * Created 10/23/2015
 *
 * @author Jesse Strife
 * @package Kernel\Engine
 * @copyright Strife Framework
 * @license MIT License
 */
use Kernel\Errors;

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
    private static $controller = null;

    /**
     * default method
     *
     * @var string
     */
    private static $method = null;

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
    private static $request_method = null;

    /**
     * in case controllers are using namespace
     *
     * @var string
     */
    private static $namespace = null;

    /**
     * Holds the route name of the currently
     * processed route.
     *
     * @var string
     */
    private static $route_name = null;

    /**
     * Original path thrown by .htaccess
     *
     * @var string
     */
    private static $original_path = null;


    /**
     * Parse and match the URL to each assigned routes and calls
     * the assigned controller and it's method along with the
     * parameters based on array given in the get() method.
     *
     * @return resource
     * @var $originalURL (contains raw URL)
     * @var $route_list (has all route listings from config/routes.php)
     * @var $parameter_count (counts params per route item e.g. /:str/:int = 2)
     * @var $parameter_types (array of rules from a route item e.g. :str,:int)
     * @var $route_base_url (base url e.g. /foo/bar w/ params sliced)
     * @var $raw_base_url (base url from originalUrl w/o params)
     * @var $raw_parameters (has all the parameters from originalUrl)
     * @var $validate_base_url (closure function to handle anomaly with loops)
     * @var $url_match_counter (counts all matched route entities)
     * @var $match_base_url_result (hols the result of entities being matched)
     * @var $match_parameters_to_rules (function closure to resolve anomaly with loopss)
     * @var $match_rules_result (holds bool from matching rules)
     * @returns resource
     */
    public function __construct()
    {
        session_start();
        $originalUrl = self::parseURL();
        error_reporting(DISPLAY_ERRORS);

        if (MAINTENANCE_MODE == TRUE) {
            return page_error(503);
        }

        if (empty($originalUrl)) {
            return self::dispatch();
        } else {
            foreach (array_values(self::$routes) as $route) {
                $route_list = explode('/', trim($route['url'], '/'));
                $parameter_count = $route['parameter_count'];

                if ($_SERVER['REQUEST_METHOD'] == "POST") {
                    if ($route['request_method'] !== "POST") {
                        continue;
                    }
                }
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                    if ($route['request_method'] == 'POST') {
                        continue;
                    }
                }
                if (count($route_list) == count($originalUrl)) {
                    $parameter_types = $route['parameter_types'];
                    $route_base_url  = array_slice($route_list, 0,  count($route_list)  - $parameter_count);
                    $raw_base_url    = array_slice($originalUrl, 0, count($originalUrl) - $parameter_count);
                    $raw_parameters  = array_slice($originalUrl,    count($originalUrl) - $parameter_count);

                    if (count($raw_base_url) == count($route_base_url)) {
                        $validate_base_url = function ($raw_base_url, $route_base_url) {
                            $url_match_counter = 0;

                            for ($counter = 0; $counter < count($raw_base_url); $counter++) {
                                if (self::$strict == true) {
                                    if ($raw_base_url[$counter] == $route_base_url[$counter]) {
                                        $url_match_counter++;
                                        continue;
                                    } else {
                                        $url_match_counter = 0;
                                        break;
                                    }
                                } else {
                                    if (preg_match("/^{$raw_base_url[$counter]}$/i", $route_base_url[$counter])) {
                                        $url_match_counter++;
                                        continue;
                                    } else {
                                        $url_match_counter = 0;
                                        break;
                                    }
                                }
                            }
                            if ($url_match_counter == count($raw_base_url)) {
                                return true;
                            } else {
                                return false;
                            }
                        };
                        $match_base_url_result = call_user_func($validate_base_url, $raw_base_url, $route_base_url);

                        if ($match_base_url_result == false) {
                            continue;
                        }
                        else {
                            if (empty($raw_parameters)) {
                                if (isset($route['closure'])) {
                                    return call_user_func_array($route['closure'], $raw_parameters);
                                }
                                self::$route_name     = $route['name'];
                                self::$method         = $route['method'];
                                self::$controller     = $route['controller'];
                                self::$namespace      = $route['namespace'];
                                self::$parameters     = $raw_parameters;
                                self::$subdirectory   = $route['subdirectory'];
                                self::$request_method = $route['request_method'];
                                return self::dispatch();
                            } else {
                                $match_parameters_to_rules = function ($parameter_types, $raw_parameters) {
                                    $rules_match_counter = 0;
                                    foreach ($parameter_types as $index => $type) {
                                        if ($type == ':int' && is_numeric($raw_parameters[$index])):
                                            $rules_match_counter++;
                                            continue;
                                        elseif ($type == ':str' && ctype_alpha($raw_parameters[$index])):
                                            $rules_match_counter++;
                                            continue;
                                        elseif ($type == ':any'):
                                            $rules_match_counter++;
                                            continue;
                                        else:
                                            continue;
                                        endif;
                                    }
                                    if ($rules_match_counter == count($raw_parameters)) {
                                        return true;
                                    }else {
                                        return false;
                                    }
                                };
                                $match_rules_result = call_user_func($match_parameters_to_rules, $parameter_types, $raw_parameters);

                                if ($match_rules_result == true) {
                                    if (isset($route['closure'])) {
                                        return call_user_func_array($route['closure'], $raw_parameters);
                                    }
                                    self::$route_name     = $route['name'];
                                    self::$controller     = $route['controller'];
                                    self::$method         = $route['method'];
                                    self::$parameters     = $raw_parameters;
                                    self::$subdirectory   = $route['subdirectory'];
                                    self::$namespace      = $route['namespace'];
                                    self::$request_method = $route['request_method'];
                                    return self::dispatch();
                                } else {
                                    continue;
                                }
                            }
                        }
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            }
            if (FILE_BASED_ROUTING) {
                return $this->FileBaseRouter();
            } else {
                return $this->DefaultErrorPage();
            }
        }
    }


    /**
     * This will be a fallback rule if declared routes didn't match any.
     * You must strictly name your contollers Pascal Case as well as if you
     * have the CONTROLLER_POST_FIX set to something, your class should too.
     * e.g. CONTROLLER_POST_FIX = 'Controller', your class should be TestController.php
     *
     * If url is only /test, we assume that class has a method called index()
     *
     * @return resource
     */
    private function FileBaseRouter()
    {
        $url_paths = explode('/',trim(self::$original_path,'/'));
        $controller_base_name = ucfirst(filter_var($url_paths[0], FILTER_SANITIZE_URL)).CONTROLLER_POST_FIX;
        $controller_full_path = ltrim(CONTROLLERS_PATH,'/') . "$controller_base_name.php";
        $url_parameters = array();

        if (count($url_paths) == 1) {
            $method = "index";
        }
        if (count($url_paths) >= 2) {
            $method = $url_paths[1];
        }
        if (count($url_paths) > 2) {
            $url_parameters = array_splice($url_paths,2);
        }

        if (file_exists($controller_full_path)) {
            require_once($controller_full_path);
            if (method_exists(new $controller_base_name, $method)) {
                call_user_func_array([new $controller_base_name, $method], $url_parameters);
            } else {
                return $this->DefaultErrorPage();
            }
        } else {
            return $this->DefaultErrorPage();
        }
    }



    /**
     * Throws a default error page.
     */
    private function DefaultErrorPage()
    {
        return $_SERVER['REQUEST_METHOD'] == "POST" ? header("HTTP/1.0 404 Not Found") : page_error(404);
    }
    


    /**
     * Parse the URL if present.
     *
     * @return array
     */
    private static function parseURL()
    {
        $url = array();
        if (isset($_GET['_url'])) {
            $url = filter_var($_GET['_url'], FILTER_SANITIZE_URL);
            $url = preg_replace('/\[/', '\[', $url);
            $url = preg_replace('/\]/', '\]', $url);
            $url = preg_replace('/\(/', '\(', $url);
            $url = preg_replace('/\)/', '\)', $url);
            $url = explode('/', trim($url, '/'));
            self::$original_path = $_GET['_url'];
            unset($_GET['_url']);
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
     * @param string $request_method
     * @return bool
     * @var string $sub
     * @var array $data
     * @var mixed $name
     * @var int $param
     * @var string $path
     */
    public static function assign($url, $action, $request_method = null, $namespace = null)
    {
        if (is_array($url)):
            $name = array_keys($url)[0];
            $url = array_values($url)[0];
        elseif (preg_match('/(.*)\-\>(.*)/', $url)):
            $data = explode('->', str_replace(' ', '', $url));
            $name = $data[0];
            $url = $data[1];
        else:
            $name = $url;
        endif;

        self::$routes[$name]['name'] = $name;
        self::$routes[$name]['url'] = $url;
        self::$routes[$name]['parameter_types'] = array();
        self::$routes[$name]['request_method'] = $request_method;
        self::$routes[$name]['namespace'] = $namespace;

        $parameter_types = array();
        $paramScan = explode('/', trim($url, '/'));

        foreach ($paramScan as $datatype) {
            if ($datatype == ':int' || $datatype == ':str' || $datatype == ':any') {
                $parameter_types[] = $datatype;
            }
        }

        if (empty($parameter_types)):
            self::$routes[$name]['parameter_count'] = 0;
        else:
            self::$routes[$name]['parameter_count'] = count($parameter_types);
            self::$routes[$name]['parameter_types'] = $parameter_types;
        endif;

        if (is_callable($action)):
            return self::$routes[$name]['closure'] = $action;
        endif;

        if (!preg_match('/\@/', $action)):
            return Errors::trigger('routes.php', "Argument passed via assign() doesn't have a valid separator '@' in routes.php", 1);
        endif;

        $path = explode('/', trim($action, '/'));

        self::$routes[$name]['controller'] = explode('@', $path[count($path) - 1])[0];
        self::$routes[$name]['method'] = explode('@', trim($path[count($path) - 1], '()'))[1];

        if (preg_match('/\//', $action)):
            $sub = "";
            for ($i = 0; $i < count($path) - 1; $i++):
                $sub = $sub . $path[$i] . '/';
            endfor;
            self::$routes[$name]['subdirectory'] = trim($sub, '/') . '/';
        else:
            self::$routes[$name]['subdirectory'] = null;
        endif;
    }


    /**
     * Instantiate the controller and call the method along
     * with the parameters if present.
     *
     * @return mixed
     */
    private static function dispatch()
    {
        if (is_null(self::$controller) && is_null(self::$method)) {
            $controller = self::$namespace . DEFAULT_CONTROLLER;
            $method = DEFAULT_METHOD;
        } else {
            $controller = self::$namespace . self::$controller;
            $method = self::$method;
        }

        require_once ('.' . CONTROLLERS_PATH . self::$subdirectory . $controller . '.php');

        if (method_exists(new $controller, $method)) {
            if (SMART_CACHE) {
                $cache_name = md5(self::$route_name) . ".cache";
                $cache_path = "{$_SERVER['DOCUMENT_ROOT']}/storage/cache/$cache_name";

                if (self::cache_exists($cache_name)) {
                    if (self::is_cache_expired($cache_path)) {
                        ob_start();
                        print (call_user_func_array([new $controller(), $method], self::$parameters));
                        $output_buffer = ob_get_clean();
                        file_put_contents($cache_path, $output_buffer);
                        return print $output_buffer;
                    } else {
                        return print self::get_cache_file($cache_name);
                    }
                } else {
                    ob_start();
                    print (call_user_func_array([new $controller(), $method], self::$parameters));
                    $output_buffer = ob_get_clean();
                    file_put_contents($cache_path, $output_buffer);
                    return print $output_buffer;
                }
            } else {
                return print (call_user_func_array([new $controller(), $method], self::$parameters));
            }
        } else {
            return Errors::trigger(app_path() . 'routes.php', "Method '$method' does not exist in '$controller' class.", 1);
        }
    }


    /**
     * Replication of assign() method to
     * set route to accept post request only
     *
     * @param string $url
     * @param string $action
     * @param string $request_method
     * @param string $namespace
     * @return mixed
     */
    public static function post($url, $action, $request_method = 'POST', $namespace = null)
    {
        return self::assign($url, $action, $request_method, $namespace);
    }


    /**
     * Check if a cache file exists
     *
     * @param $cache_name
     * @return array
     */
    public static function cache_exists($cache_name)
    {
        return file_exists("{$_SERVER['DOCUMENT_ROOT']}/storage/cache/$cache_name");
    }


    /**
     * Check if a cache file is expired
     *
     * @param $cache_name
     * @return array
     */
    public static function is_cache_expired($cache_path)
    {
        $time_passed = time() - filemtime($cache_path);
        return $time_passed > CACHE_EXPIRATION ? true : false;
    }


    /**
     * Check if a cache file exists
     *
     * @param $cache_name
     * @return array
     */
    public static function get_cache_file($cache_name)
    {
        return file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/storage/cache/$cache_name");
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
     * Set whether strict URL matching on character casing
     * should be performed
     *
     * @param $bool
     * @return bool
     */
    public function strict($bool)
    {
        if ($bool == true):
            self::$strict = true;
        else:
            self::$strict = false;
        endif;
    }
}