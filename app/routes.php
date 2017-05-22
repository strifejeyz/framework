<?php

/**
 * List of route paths
 */
assign('homepage ->> /', 'HomeController::index');
assign('welcome  ->> /welcome', '/cms/WelcomeController::index');


/**
 * Authentication routes
 */
assign('auth.login  ->> /login', 'AuthController::index');
post('auth.attempt  ->> /attempt', 'AuthController::attempt');
assign('auth.logout ->> /logout', 'AuthController::logout');
