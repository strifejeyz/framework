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
        $title = "Home Page";

        return View::render('index', compact('title'));
    }
}
