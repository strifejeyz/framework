<?php namespace App\Requests;

use Kernel\Requests\HTTPRequest;

class LoginRequest extends HTTPRequest
{

    /**
     * Rules to be followed by request
     */
    protected $rules = [
        'username' => 'name:Username|required',
        'password' => 'name:Password|required'
    ];

}