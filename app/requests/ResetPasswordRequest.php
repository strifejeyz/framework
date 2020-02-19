<?php namespace App\Requests;

use Kernel\Requests\HTTPRequest;

class ResetPasswordRequest extends HTTPRequest
{

    /**
     * Rules to be followed by request
     */
    protected $rules = [
        'new_password' => 'name:New password|required|min:8',
        'confirm_password' => 'name:Confirm password|required|min:8|match:new_password'
    ];

}