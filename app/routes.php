<?php

/**
 * List of route paths
 *
 * Here's an example of a simple route:
 * assign('MyTestRoute ->> /test', 'TestController@index()')
 */

assign('welcome -> /welcome', '/cms/WelcomeController@index');



/**
 * Authentication & Password
 * reset routes
 */
assign('auth.login         -> /login', 'AuthController@index');
assign('auth.logout        -> /logout', 'AuthController@logout');
post('auth.attempt         -> /attempt', 'AuthController@attempt');
assign('auth.forgot.index  -> /forgot-password', 'AuthController@forgotPassword');
post('auth.forgot.email    -> /forgot-password/send-email', 'AuthController@sendEmail');
assign('auth.forgot.failed -> /password-reset/failed', 'AuthController@passwordTokenFailed');
post('auth.forgot.update   -> /password-reset/update', 'AuthController@updatePassword');
assign('auth.forgot.token  -> /password-reset/:token', 'AuthController@verifyToken');
