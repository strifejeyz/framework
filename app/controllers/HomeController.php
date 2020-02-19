<?php

/**
 * Default controller
 * Class HomeController
 */
class HomeController
{
    /**
     * @return View
     */
    public function index()
    {
        return View::render('frontend/index');
    }
}
