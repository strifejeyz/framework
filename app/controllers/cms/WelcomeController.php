<?php

class WelcomeController extends Auth
{
    /**
     * Controller Index
     * @return view
     **/
    public function index()
    {
        $name = Session::user()->firstname;

        return render('backend/welcome', compact('name'));
    }
}
