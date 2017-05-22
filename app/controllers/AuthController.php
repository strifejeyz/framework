<?php

class AuthController
{

    /**
     * Show Login View
     *
     * @return view
     */
    public function index()
    {
        return View::render('auth/login');
    }

    /**
     * Login a user
     *
     * @return mixed
     */
    public function attempt()
    {
        $login = new LoginProcess;
        return $login->execute(function () {
            return Route::redirect('login');
        });
    }

    /**
     * Logout
     *
     * @return Route
     */
    public function logout()
    {
        return Session::destroy();
    }
}