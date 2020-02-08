<?php /** @noinspection PhpUnused */

use App\Models\User;
use App\Models\Tokens;
use App\Models\Security;
use Kernel\Security\Hash;
use Kernel\Security\Token;
use Kernel\Database\Database;
use App\Requests\LoginRequest;
use Kernel\Security\Encryption;
use Kernel\Requests\HTTPRequest;
use App\Requests\ResetPasswordRequest;

/**
 * This is a highly customizable login and
 * password reset template, feel free.....
 **/
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
     * @throws ErrorHandler
     */
    public function attempt()
    {
        $request = new LoginRequest;

        if ($request->validate()) 
        {
            if (preg_match('/(.*)@(.*)/',$request->get('username'))) {
                $field = "email";
            } else {
                $field = "username";
            }
            $user = User::where($field, $request->get('username'))
                ->where('password', Hash::encode($request->get('password')))
                ->where('active', 'yes')->first();

            # if user exists and active.
            if (!empty($user)) {
                $checkToken = new Database;
                $checkToken = $checkToken->row("SELECT * FROM tokens WHERE user_id=? AND failed_login > 4 LIMIT 1", [$user->id]);

                if (!empty($checkToken)) {
                    setFlash('flash', '<div class="alert alert-danger">Your account has been disabled.</div>');
                    return redirect('/login');
                } else {
                    # Yay! successfully logged in. now save the session
                    # and delete any tokens (if there's any)
                    $user->remember_token = Token::create();
                    $user->save();
                    $_SESSION['user'] = $user();
                    Tokens::where('user_id', $user->id)->delete();

                    # if supposed to visit a link, it takes you there after login
                    if (intended()) {
                        return redirect(intended());
                    } else {
                        return redirect('/welcome');
                    }
                }
            }

            # ==========================================================
            # If user does exist, we'll increment 'failed_login' field
            # every failed login attempt.

            else {
                $isExistingUser = User::where($field, $request->get('username'))->first();
                if (!empty($isExistingUser)) {
                    $tokenEntry = Tokens::where('user_id', $isExistingUser->id)->first();
                    if (!empty($tokenEntry)) {
                        if ($tokenEntry->failed_login >= 5) {
                            setFlash('flash', '<div class="alert alert-danger">Your account has been disabled.</div>');
                            return redirect('/login');
                        } else {
                            $token = Tokens::where('user_id', $isExistingUser->id)->first();
                            $token->increment('failed_login');
                            $token->updated = time();
                            $token->save();
                            setFlash('flash', '<div class="alert alert-warning">username or password is incorrect.</div>');
                            return redirect('/login');
                        }
                    } else {
                        Tokens::insert([
                            'user_id' => $isExistingUser->id,
                            'reset_attempts' => 0,
                            'created' => time(),
                            'updated' => time(),
                            'failed_login' => 1
                        ]);
                        setFlash('flash', '<div class="alert alert-warning">username or password is incorrect.</div>');
                        return redirect('/login');
                    }
                } else {
                    setFlash('flash', '<div class="alert alert-warning">username or password is incorrect.</div>');
                    return redirect('/login');
                }
            }
        } else {
            return redirect('/login');
        }
    }


    /**
     * Password reset page
     * contains email/sec questions
     *
     * @return Route
     */
    public function forgotPassword()
    {
        return render('/auth/forgot');
    }


    /**
     * Password reset attempt
     *
     * @return Route
     * @throws ErrorHandler
     */
    public function sendEmail()
    {
        $request = new HTTPRequest;
        $user = User::where('email', $request->get('email', true))->first();
        $url = 'http://strife.local/password-reset';

        # if email/username is valid
        if (!empty($user)) {
            $userToken = Tokens::whereUser_Id($user->id)->first();
            $createdToken = Encryption::encode(Token::create() . '#' . $user->email);
            # check if there's no token entry yet.
            if (empty($userToken)) {
                Tokens::insert([
                    'user_id' => $user->id,
                    'reset_attempts' => 1,
                    'created' => time(),
                    'updated' => time(),
                    'reset_token' => $createdToken,
                    'token_expiration' => time() + 1800
                ]);

                $this->mailPassword($user->email, "http://strife.local/password-reset/{$createdToken}");
            } # There's already a token entry for the user.
            else {
                # check if user tried 5 times, then blocked
                if ($userToken->reset_attempts >= 5) {
                    setflash("message", "<div class='alert alert-success'>Your access have been disabled for excessive attempts, please contact system administrator.</div>");
                } # if < 5, then good for another.
                else {
                    $userToken->updated = time();
                    $userToken->increment('reset_attempts');
                    $userToken->reset_token = $createdToken;
                    $userToken->token_expiration = time() + 3600;
                    $userToken->save();

                    $this->mailPassword($user->email, "$url/$createdToken");
                    setflash("message", "<div class='alert alert-success'>If this is the right email, we will send a link to reset your password.</div>");
                }
            }

            # redirect to the same window for results
            return redirect('/forgot-password');
        } # if not in db, say nothing.
        else {
            # because we don't want to give them a hint that the email they entered is correct.
            # security wise
            setflash("message", "<div class='alert alert-danger'>We cannot process your request this time, please try again later.</div>");
            return redirect('/forgot-password');
        }
    }


    /**
     * Mail the password to the email
     *
     * @param $receiver
     * @param $url
     * @return void
     */
    private function mailPassword($receiver, $url)
    {
        $email = $receiver;
        $subject = "Password Reset Link";
        $message = "Here's the password reset link for you: $url";
        $headers = 'From: webmaster@strife.dev' . "\r\n" .
            'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($email, $subject, $message, $headers);
    }


    /**
     * This method will function after a user
     * clicks on the link sent via email.
     *
     * @param $token
     * @return void
     */
    public function verifyToken($token)
    {
        $values = explode('#', Encryption::decode($token));
        $user = User::where('email', $values[1])
            ->orWhere('username', $values[1])
            ->first();

        if (!empty($user)) {
            $authToken = Tokens::where('user_id', $user->id)->first();

            # if token is the same and orig timestamp is still w/in an hour.
            if ($authToken->reset_token == $token && time() < $authToken->token_expiration) {
                return render('auth/reset', compact('authToken'));
            } else {
                return redirect("/password-reset/failed");
            }
        }
    }

    /**
     * Password reset link has expired
     *
     * @return void
     */
    public function passwordTokenFailed()
    {
        return render('/auth/expired');
    }


    /**
     * Update the password
     *
     * @return void
     */
    public function updatePassword()
    {
        $request = new ResetPasswordRequest;
        $return_url = "/password-reset/" . $request->get('reset_token');

        if ($request->validate()) {
            $user = User::find($request->get('user_id'));
            $user->password = Hash::encode($request->get('new_password'));
            $user->save();

            # clear the tokens
            $token = Tokens::where('user_id', $user->id)->first();
            $token->delete();

            setflash('message', "<div class='alert alert-success'>You've successfully changed your password.</div>");
            # successfully changed the password.
            return redirect('/login');
        } else {
            return redirect($return_url);
        }
    }

    /**
     * Logout
     *
     * @return Route
     */
    public function logout()
    {
        return Session::destroy(route('auth.login'));
    }
}