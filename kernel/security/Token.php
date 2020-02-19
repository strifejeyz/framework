<?php namespace Kernel\Security;

/**
 * Class Token
 *
 * @package Kernel
 */
class Token
{

    /**
     * Create a token
     *
     * @return string
     */
    public static function create()
    {
        return md5(session_id() . APPLICATION_KEY . $_SERVER['HTTP_USER_AGENT']);
    }


    /**
     * Verify whether a token is unchanged.
     *
     * @param $token
     * @return boolean
     */
    public static function verify($token)
    {
        if ($token === self::create()) {
            return true;
        } else {
            return false;
        }
    }
}