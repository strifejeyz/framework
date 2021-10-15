<?php /** @noinspection PhpUnused */

use Kernel\Mail;
use App\Models\User;
use App\Models\Tokens;
use Kernel\Security\Hash;
use Kernel\Security\Token;
use Kernel\Database\Database;
use App\Requests\LoginRequest;
use Kernel\Security\Encryption;
use Kernel\Requests\HTTPRequest;
use App\Requests\ResetPasswordRequest;

/**
 * This is a fully customizable login and
 * password reset template, feel free.....
 **/
class AuthController
{
    /**
     * If login is successful, redirect here
     */
    private $redirect_route = '/welcome';


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
     * @return string
     */
    public function attempt()
    {
        $request = new LoginRequest;
        if ($request->validate()) {
            $user = User::where('username', $request->get('username'))->where('password', Hash::encode($request->get('password')))->where('active', 'yes')->first();
            if (!empty($user)) {
                $checkToken = new Database;
                $checkToken = $checkToken->row("SELECT * FROM tokens WHERE user_id=? AND failed_login > 4 LIMIT 1", [$user->id]);

                if (!empty($checkToken)) {
                    $this->setFlash('flash','Your account has been disabled.');
                    return redirect('/login');
                } else {
                    $user->remember_token = Token::create();
                    $user->save();
                    $_SESSION['user'] = $user();
                    Tokens::where('user_id', $user->id)->delete();

                    if (intended()) {
                        return redirect(intended());
                    } else {
                        return redirect($this->redirect_route);
                    }
                }
            }
            else {
                $isExistingUser = User::where('username', $request->get('username'))->first();
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
                            'user_id'        => $isExistingUser->id,
                            'reset_attempts' => 0,
                            'created'        => time(),
                            'updated'        => time(),
                            'failed_login'   => 1
                        ]);
                        $this->setFlash('flash','username or password is incorrect.');
                        return redirect('/login');
                    }
                } else {
                    $this->setFlash('flash','username or password is incorrect.');
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
     * @return string
     */
    public function sendEmail()
    {
        $request = new HTTPRequest;
        $user = User::where('email', $request->get('email', true))->first();
        $url  = BASE_URL . 'password-reset';

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
                $this->mailPassword($user->email, "$url/$createdToken");
            }
            else {
                if ($userToken->reset_attempts >= 5) {
                    $this->setFlash('message','Your access have been disabled for excessive attempts, please contact system administrator.');
                }
                else {
                    $userToken->updated = time();
                    $userToken->increment('reset_attempts');
                    $userToken->reset_token = $createdToken;
                    $userToken->token_expiration = time() + 3600;
                    $userToken->save();
                    $this->mailPassword($user->email, "$url/$createdToken");
                    $this->setFlash('message','If this is the right email, we will send a link to reset your password.');
                }
            }

            return redirect('/forgot-password');
        }
        else {
            $this->setFlash('message','We cannot process your request this time, please try again later.');
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
    private function mailPassword($recipient, $url)
    {
        $mail = new Mail();
        $mail->body("Here's the password reset link for you: $url");
        $mail->from("mail@dummy.test","Strife Framework");
        $mail->subject('Password Reset Link');
        $mail->to($recipient);
        $mail->send();
    }


    /**
     * Set a Message flash
     */
    private function setFlash($name,$message)
    {
        setflash($name, "<div class='alert alert-success'>$message</div>");
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