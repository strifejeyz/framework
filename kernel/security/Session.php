<?php

/**
 * Class Session
 */
class Session
{
    /**
     * make use of session variables
     *
     * @return mixed
     */
    public static function user()
    {
        return $_SESSION['user'];
    }


    /**
     * Get a session variable
     *
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        return (array_key_exists($name, $_SESSION)) ? $_SESSION[$name] : null;
    }


    /**
     * Set a session variable
     *
     * @param $name
     * @param $value
     * @return bool
     */
    public static function set($name, $value)
    {
        return $_SESSION[$name] = $value;
    }


    /**
     * Unset a session variable
     *
     * @param $name
     * @return mixed
     */
    public static function remove($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
            return (true);
        } else {
            return (false);
        }
    }


    /**
     * Get the session flash message if defined
     * else return an empty string
     *
     * @param $name
     * @return string
     * @var string $flash
     */
    public static function getFlash($name)
    {
        if (isset($_SESSION['__FLASH__'][$name])) {
            $message = $_SESSION['__FLASH__'][$name];
            unset($_SESSION['__FLASH__'][$name]);
            return ($message);
        } else {
            return ("");
        }
    }


    /**
     * Destroy an existing flash message or
     * remove all flash messages if parameter is null
     *
     * @param $name
     * @return mixed
     */
    public static function unsetFlash($name = null)
    {
        if (is_null($name)) {
            if (isset($_SESSION['__FLASH__'])) {
                unset($_SESSION['__FLASH__']);
                return (true);
            }
        } elseif (isset($_SESSION['__FLASH__'][$name])) {
            unset($_SESSION['__FLASH__'][$name]);
            return (true);
        } else {
            return (false);
        }
        return (true);
    }


    /**
     * Set a session flash message
     *
     * @param $name
     * @param $message
     * @return bool
     */
    public static function setFlash($name, $message)
    {
        if ($_SESSION['__FLASH__'][$name] = $message) {
            return (true);
        } else {
            return (false);
        }
    }


    /**
     * Flush all sessions except session_id
     *
     * @return mixed
     */
    public static function destroy()
    {
        unset($_SESSION);
        session_destroy();
        session_regenerate_id();

        header('location: ' . route('auth.login'));
    }
}