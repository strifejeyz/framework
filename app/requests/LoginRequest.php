<?php namespace App\Requests;

use Kernel\Requests\HTTPRequest;

class LoginRequest extends HTTPRequest
{

    /**
     * This is the route that will be used
     * to redirect when errors are present
     */
    protected $route = '/';


    /**
     * Rules to be followed by request
     */
    protected $rules = [
        'username' => 'name:Username|required',
        'password' => 'name:Password|required'
    ];

}