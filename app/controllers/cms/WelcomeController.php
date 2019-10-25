<?php

class WelcomeController extends Auth
{
    /**
     * Controller Index
     * @return view
     **/
    public function index()
    {
        $user = Session::user();

        return render('backend/welcome', compact('user'));
    }
}
