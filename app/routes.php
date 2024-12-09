<?php

/**
 * List of route paths. If file_based_routing is enabled, this file will be ignored.
 *
 * Here's an example of a simple route:
 * get('MyTestRoute -> /test', 'TestController@index()')
 */

get('/welcome', 'WelcomeController@index');


/**
 * Authentication & Password reset
 */
get('auth.login           -> /login',                     'AuthController@index');
get('auth.logout          -> /logout',                    'AuthController@logout');
post('auth.attempt        -> /attempt',                   'AuthController@attempt');
get('auth.forgot.index    -> /forgot-password',           'AuthController@forgotPassword');
post('auth.forgot.email   -> /forgot-password/send-email','AuthController@sendEmail');
get('auth.forgot.failed   -> /password-reset/failed',     'AuthController@passwordTokenFailed');
post('auth.forgot.update  -> /password-reset/update',     'AuthController@updatePassword');
get('auth.forgot.token    -> /password-reset/:token',     'AuthController@verifyToken');
