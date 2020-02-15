<?php
use App\Requests\ContactFormRequest as Request;

/**
 * List of route paths
 *
 * Here's an example of a simple route:
 * assign('MyTestRoute ->> /test', 'TestController@index()')
 *
 * Explanation:
 * 'MyTestRoute' is a route name
 * '->>' is a pointer
 * '/test' is the URL
 * 'TestController@index()' is the Class and Method
 *
 * when Route@strict(true);
 * e.g. /Foo is not equal to /foo
 */

assign('welcome ->> /welcome', '/cms/WelcomeController@index');

post('/contact/send', function(){
    $request = new Request;

    if ($request->validate() == true):

        $subject = $request->get('subject');
        $message = $request->get('message');
        $myEmail = "strifejeyz@gmail.com";

        $mail = mail($myEmail, $subject, $message, "From:contact@jessestrife.cf");

        if($mail):
            setflash('flash',"<i class='text-success'>Your message was sent, I'll reply to you the soonest!</i>");
        else:
            setflash('flash', "<i class='text-danger'>Your message could not be sent.</i>");
        endif;
    endif;

    return redirect($request->origin() . "#contact-form");
});


assign('/hire-me/cv', function(){
    download_file(assets_path() .'/files/cv.pdf', 'cv.pdf');
});



/**
 * Authentication & Password
 * reset routes
 */
assign('auth.login         ->> /login', 'AuthController@index');
assign('auth.logout        ->> /logout', 'AuthController@logout');
post('auth.attempt         ->> /attempt', 'AuthController@attempt');
assign('auth.forgot.index  ->> /forgot-password', 'AuthController@forgotPassword');
post('auth.forgot.email    ->> /forgot-password/send-email', 'AuthController@sendEmail');
assign('auth.forgot.failed ->> /password-reset/failed', 'AuthController@passwordTokenFailed');
post('auth.forgot.update   ->> /password-reset/update', 'AuthController@updatePassword');
assign('auth.forgot.token  ->> /password-reset/:token', 'AuthController@verifyToken');
