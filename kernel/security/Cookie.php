<?php

/**
 * Class Cookie
 */
class Cookie
{
    public static $path = "/";

    /**
     * Get a Cookie value
     *
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        return array_key_exists($name, $_COOKIE) ? $_COOKIE[$name] : (null);
    }


    /**
     * Set a Cookie variable
     *
     * @param $name
     * @param $value
     * @param $exp
     * @param $path
     * @param $domain
     * @param $secure
     * @param $httponly
     * @return bool
     */
    public static function set($name, $value, $exp = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        $cookiePath = isset($path) ? $path : self::$path;

        return setcookie($name, $value, $exp, $cookiePath, $domain, $secure, $httponly);
    }


    /**
     * Unset a cookie
     *
     * @param $name
     * @return mixed
     */
    public static function destroy($name)
    {
        if (isset($_COOKIE[$name])) {
            return setcookie($name, "", time() - 3600);
        } else {
            return false;
        }
    }


    /**
     * Flush all Cookies
     *
     * @return mixed
     */
    public static function flush()
    {
        $keys = array_keys($_COOKIE);

        for ($i = 0; $i < count($keys); $i++) {
            self::destroy($keys[$i]);
            print $keys[$i];
        }

        return (true);
    }

}