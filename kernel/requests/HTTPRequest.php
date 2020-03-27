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
    public function __construct();

    public function get($field);

    public function get_hash($field, $sanitized = true);

    public function set_hash($field);

    public function origin();

    public function values();

    public function raw();

    public function append($name, $value);

    public function remove($name);

    public function retain();

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
     * Holds the URI who sent the request.
     * useful for redirecting to the same page it came from.
     */
    private $origin_uri = null;

    /**
     * Catches request method, and filter
     * each values
     *
     * @param $method
     */
    public function __construct($method = "post")
    {
        if ($method == "post") {
            if (array_key_exists('__FORM_ORIGIN__', $_POST)):
                $this->origin_uri = $_POST['__FORM_ORIGIN__'];
                unset($_POST['__FORM_ORIGIN__']);
            endif;

            if (array_key_exists('__FORM_TOKEN__', $_POST)):
                $token = $_POST['__FORM_TOKEN__'];
                unset($_POST['__FORM_TOKEN__']);

                if (!Token::verify($token)):
                    $auth = new Auth();
                    return $auth->restartSession();
                endif;
            endif;

            $this->request = filter_var_array($_POST, FILTER_SANITIZE_STRIPPED);

        } else {
            if (array_key_exists('__FORM_ORIGIN__', $_GET)):
                $this->origin_uri = $_GET['__FORM_ORIGIN__'];
                unset($_GET['__FORM_ORIGIN__']);
            endif;

            if (array_key_exists('__FORM_TOKEN__', $_GET)):
                $token = $_GET['__FORM_TOKEN__'];
                unset($_GET['__FORM_TOKEN__']);

                if (!Token::verify($token)):
                    $auth = new Auth();
                    return $auth->restartSession();
                endif;
            endif;

            $this->request = filter_var_array($_GET, FILTER_SANITIZE_STRIPPED);
        }
    }


    /**
     * Returns dynamically called property
     * if it exists in $this->>request
     *
     * @param $property
     * @return string
     */
    public function __get($property)
    {
        if (array_key_exists($property, $this->request)):
            return $this->request[$property];
        else:
            return null;
        endif;
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
     * Returns a hashed value
     * $request property.
     *
     * @param $field
     * @param $sanitized bool
     * @return string
     */
    public function get_hash($field, $sanitized = true)
    {
        return Hash::encode($this->get($field, $sanitized));
    }


    /**
     * Returns a field that is present in
     * $request property encrypted thru hash.
     *
     * @param $field
     * @return string
     */
    public function set_hash($field)
    {
        $this->request[$field] = Hash::encode($this->request[$field]);
    }


    /**
     * Returns the original URI where the form
     * come from. Usually useful for redirecting
     * to the same form after let's say failed validation
     * and stuff like that.
     *
     * @return string
     */
    public function origin()
    {
        return $this->origin_uri;
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
     * returns raw request, equivalent to $_POST
     * the __FORM_TOKEN__ and __FORM_ORIGIN__ are removed from __constructor
     * as it only check for form validity for XSS purposes.
     *
     * @return string
     */
    public function raw()
    {
        return $_POST;
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
     * For special scenarions where you validate()
     * is set to true but you have to redirect for
     * there's a form error, this can retain the form
     * values and is accessible via fields('field_name')
     *
     * @return mixed
     */
    public function retain()
    {
        return $_SESSION['__FIELDS__'] = $this->request;
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
     * Rules List:
     * -> name:First Name
     * -> required
     * -> unique:TableName
     * -> email
     * -> alphanumeric
     * -> letters
     * -> numbers
     * -> numeric
     * -> min:(number here)
     * -> max:(number here)
     * -> match:TextFieldName
     *
     * e.g. $rules = [
     *     'password' => 'min:8|max:50|unique:users|match:ConfirmPassword'
     * ];
     *
     * @return bool|void
     */
    public function validate()
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

                    if (preg_match('/unique\:/i', $rule[$z])) {
                        $db = new Database;
                        $value = $this->request[$field[$i]];
                        foreach ($rule as $item) {
                            if ($item == 'password') {
                                $value = Hash::encode($value);
                                break;
                            }
                        }

                        $query = "SELECT * FROM " . explode(':', $rule[$z])[1] . " WHERE {$field[$i]}='$value'";
                        $row = $db->row($query);

                        if ($row == true) {
                            $this->errors[$field[$i]] = $prefix . " is not available.";
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
                    if ($rule[$z] == 'numbers' || $rule[$z] == 'numeric') {
                        if (!preg_match('/[0-9]/', $this->request[$field[$i]])) {
                            $this->errors[$field[$i]] = $prefix . " should be numeric.";
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
                    if (preg_match('/match/i', $rule[$z])) {
                        $compare = explode(':', $rule[$z])[1];
                        if ($this->request[$field[$i]] !== $this->request[$compare]) {
                            $this->errors[$field[$i]] = $prefix . " Field did not match to " . str_replace('_', ' ', $compare) . ".";
                            $this->errors[$compare] = " Field did not match to " . str_replace('_', ' ', $prefix) . ".";
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
        if (empty($this->errors)) {
            return true;
        } else {
            $_SESSION['__ERRORS__'] = $this->errors;
            $_SESSION['__FIELDS__'] = $this->request;
            return false;
        }
    }
}