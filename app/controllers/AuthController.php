<?php

use App\Models\User;
use Kernel\Security\Hash;
use Kernel\Security\Token;
use App\Requests\LoginRequest;

class AuthController
{

    /**
     * Show Login View
     *
     * @return view
     */
    public function index()
    {
        return View::render('auth/login');
    }

    /**
     * Login a user
     *
     * @return mixed
     */
    public function attempt()
    {
        $request = new LoginRequest($_POST);

        if ($request->validate()) {
            $attempt = User::where('username', $request->get('username'))
                ->where('password', Hash::encode($request->get('password')))
                ->where('active', 'yes');

            if ($attempt->exists()) {
                $user = $attempt->first();
                $user->remember_token = Token::create();
                $user->save();
                $_SESSION['user'] = $user();

                if (intended()) {
                    return redirect(intended());
                } else {
                    Route::redirect(route('welcome'));
                }
            } else {
                Session::setFlash('flash', '<i class="text-danger">username or password is incorrect.</i><br><br>');
            }
        }

        return Route::redirect(route('auth.login'));
    }

    /**
     * Logout
     *
     * @return Route
     */
    public function logout()
    {
        return Session::destroy();
    }
}