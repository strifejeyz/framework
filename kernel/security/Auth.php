<?php
use Kernel\Security\Token;

/**
 * Class Auth
 *
 * @package Kernel
 */
class Auth
{
    /**
     * Start Auth
     **/
    public function __construct()
    {
        return self::guard();
    }


    /**
     * Determines whether a user is authenticated
     * by checking keys if they are valid.
     *
     * @return mixed
     **/
    public function guard()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['__INTENDED__'] = $_SERVER['REQUEST_URI'];
            return Route::redirect(route('auth.login'));
        } else {
            Session::remove('__INTENDED__');

            if (!Token::verify(Session::user()->remember_token)) {
                return $this->restartSession();
            }
        }

        return (true);
    }


    /**
     * Check whether user is authenticated
     * if not, reset session and redirect
     * to login page.
     *
     * @return mixed
     **/
    public function restartSession()
    {
        return Session::destroy();
    }
}