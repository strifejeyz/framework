<?php

use App\Requests\ContactFormRequest as Request;


assign('welcome ->> /welcome', '/cms/WelcomeController@index');

post('/contact/send', function () {
    $request = new Request;

    if ($request->validate() == true):
        $from = "From:contact@jessestrife.cf";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: '.$from."\r\n".
            'Reply-To: '.$from."\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $subject = $request->get('subject');
        $message = "<h4><br>Sender: {$request->get('name')}</br>";
        $message.= "<br>E-mail: {$request->get('email')}</br>";
        $message.= "<br>Subject: {$request->get('subject')}</br>";
        $message.= "<br>Message: {$request->get('message')}</h4>";
        $myEmail = "strifejeyz@gmail.com";



        $mail = mail($myEmail, $subject, $message, $headers);

        if ($mail):
            setflash('flash', "<i class='text-success'>Your message was sent, I'll reply to you the soonest!</i>");
        else:
            setflash('flash', "<i class='text-danger'>Your message could not be sent.</i>");
        endif;
    endif;

    return redirect($request->origin() . "#contact-form");
});


assign('/hire-me/cv', function () {
    download_file(assets_path() . '/files/cv.pdf', 'cv.pdf');
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
