<?php namespace App\Requests;

use Kernel\Requests\HTTPRequest;

class ContactFormRequest extends HTTPRequest
{

    /**
     * Rules to be followed by request
     */
    protected $rules = [
        'name'    => 'name:Your name|required',
        'email'   => 'name:E-mail|required|email',
        'subject' => 'name:Subject|required',
        'message' => 'name:Your message|required|min:20|max:250',
    ];

}