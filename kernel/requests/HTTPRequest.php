<?php namespace Kernel\Requests;

use Kernel\Database\Database;
use Kernel\Security\Hash;
use Kernel\Security\Token;

/**
 * Interface RequestInterface
 *
 * @package Kernel
 */
interface HTTPRequestInterface
{
    public function __construct($request);

    public function get($field);

    public function values();

    public function sanitize($string);

    public function validate();
}

/**
 * Request class, this will handle validations and rules for requests.
 * validation includes: email,number,file,text maximum and minimum values,
 * and can determine whether a database unique value is taken.
 *
 * Class Request
 *
 * @package Kernel
 */
class HTTPRequest implements HTTPRequestInterface
{
    /**
     * Storage for request method
     */
    public $request = null;

    /**
     * Storage for field errors
     */
    public $errors = null;


    /**
     * Catches request method, and filter
     * each values
     *
     * @param $request
     */
    public function __construct($request = null)
    {
        $request = is_null($request) ? $_POST : $request;

        if (is_null($this->request)) {
            if (array_key_exists('__FORM_TOKEN__', $request)) {
                unset($request['__FORM_TOKEN__']);
            }
            $this->request = filter_var_array($request, FILTER_SANITIZE_STRIPPED);
        }
        if (array_key_exists('__FORM_TOKEN__', $_POST)) {
            $token = $_POST['__FORM_TOKEN__'];
            unset($_POST['__FORM_TOKEN__']);

            if (!Token::verify($token)) {
                $auth = new \Auth();
                return $auth->restartSession();
            }
        }

        return (true);
    }


    /**
     * Returns a field that is present in
     * $request property that is set beforehand.
     *
     * @param $field
     * @param $sanitized bool
     * @return string
     */
    public function get($field, $sanitized = true)
    {
        if (array_key_exists($field, $this->request)) {
            if ($sanitized) {
                return $this->sanitize($this->request[$field]);
            } else {
                return $this->request[$field];
            }
        } else {
            return (null);
        }
    }


    /**
     * returns request array ready for insertion
     *
     * @return string
     */
    public function values()
    {
        return ($this->request);
    }


    /**
     * Append specific value to array
     *
     * @param $name
     * @param $value
     * @return string
     */
    public function append($name, $value)
    {
        return ($this->request[$name] = $value);
    }


    /**
     * Append specific value to array
     *
     * @param $name
     * @return mixed
     */
    public function remove($name)
    {
        if (array_key_exists($name, $this->request)) {
            unset($this->request[$name]);
        } else {
            return (false);
        }
    }


    /**
     * Store current field values
     * to fields session var and can be accessed thru
     * fields() method
     *
     * @return string
     */
    public function retain()
    {
        return ($_SESSION['__FIELDS__'] = $this->request);
    }


    /**
     * Returns a sanitized string
     *
     * @param $string
     * @return string
     */
    public function sanitize($string)
    {
        return filter_var($string, FILTER_SANITIZE_STRING);
    }


    /**
     * Actual validation of request with rules implied
     * from its child classes.
     *
     * @param null $route
     * @return bool|void
     */
    public function validate($route = null)
    {
        for ($i = 0; $i < count($this->request); $i++) {
            $field = array_keys($this->request);
            if (array_key_exists($field[$i], $this->rules)) {
                $rule = explode('|', $this->rules[$field[$i]]);
                $prefix = null;

                foreach ($rule as $alias) {
                    if (preg_match('/name\:/i', $alias)) {
                        $prefix = (explode(':', $alias)[1]);
                        break;
                    }
                }

                for ($z = 0; $z < count($rule); $z++) {
                    if (!$prefix) {
                        $prefix = str_replace('_', ' ', $field[$i]);
                    }

                    if ($rule[$z] == 'required') {
                        if (strlen($this->request[$field[$i]]) == 0) {
                            $this->errors[$field[$i]] = $prefix . " is required.";
                            break;
                        }
                    }

                    /**
                     * Value should be unique in a field
                     * on the database
                     **/
                    if (preg_match('/unique/i', $rule[$z])) {
                        $db = new Database;
                        $value = $this->request[$field[$i]];
                        foreach ($rule as $item) {
                            if ($item == 'password') {
                                $value = Hash::encode($value);
                                break;
                            }
                        }
                        if ($db->table(explode(':', $rule[$z])[1])->where($field[$i], $value)->exists()) {
                            $this->errors[$field[$i]] = $prefix . " not available.";
                            break;
                        }
                    }
                    if ($rule[$z] == 'email') {
                        if (!preg_match('/@/', $this->request[$field[$i]])) {
                            $this->errors[$field[$i]] = "Enter a valid e-mail.";
                            break;
                        }
                    }
                    if ($rule[$z] == 'alphanumeric') {
                        if (!preg_match('/[^A-Za-z0-9]/i', $this->request[$field[$i]])) {
                            $this->errors[$field[$i]] = "Only alphanumeric characters are allowed.";
                            break;
                        }
                    }
                    if ($rule[$z] == 'letters') {
                        if (!preg_match('/^[A-Za-z]/i', $this->request[$field[$i]])) {
                            $this->errors[$field[$i]] = $prefix . " accepts letters only.";
                            break;
                        }
                    }
                    if ($rule[$z] == 'number' || $rule[$z] == 'numeric') {
                        if (!preg_match('/[0-9]/', $this->request[$field[$i]])) {
                            $this->errors[$field[$i]] = $prefix . " should be numeric.";
                            break;
                        }
                    }
                    if (preg_match('/match/i', $rule[$z])) {
                        $compare = explode(':', $rule[$z])[1];
                        if ($this->request[$field[$i]] !== $this->request[$compare]) {
                            $this->errors[$field[$i]] = $prefix . " Field did not match to {$compare}.";
                            $this->errors[$compare] = " Field did not match to {$prefix}.";
                            break;
                        }
                    }
                    if (preg_match('/min/i', $rule[$z])) {
                        $min = explode(':', $rule[$z])[1];
                        if (strlen($this->request[$field[$i]]) < $min) {
                            $this->errors[$field[$i]] = $prefix . " requires a minimum of {$min} characters.";
                            break;
                        }
                    }
                    if (preg_match('/max/i', $rule[$z])) {
                        $max = explode(':', $rule[$z])[1];
                        if (strlen($this->request[$field[$i]]) > $max) {
                            $this->errors[$field[$i]] = $prefix . " requires a maximum of {$max} characters.";
                            break;
                        }
                    }
                }
            }
        }
        $fileRules = array_keys($this->rules);
        for ($f = 0; $f < count($fileRules); $f++) {
            if (array_key_exists($fileRules[$f], $_FILES)) {
                if (empty($_FILES[$fileRules[$f]]['name'])) {
                    $this->errors[$fileRules[$f]] = str_replace('_', ' ', $fileRules[$f]) . " is required.";
                }
            }
        }
        $redirectRoute = (!is_null($route)) ? $route : $this->route;
        if (is_null($this->errors)) {
            return (true);
        } else {
            $_SESSION['__ERRORS__'] = $this->errors;
            $_SESSION['__FIELDS__'] = $this->request;
            header("location: {$redirectRoute}");
        }
    }
}