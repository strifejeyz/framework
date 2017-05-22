<?php

use Kernel\Security\Token;

/**
 * Form class includes html form inputs,
 * database model binding, and form auto fill.
 *
 * Class Form
 *
 * @package Kernel
 */
abstract class Form
{
    /**
     * property that will hold the object model
     *
     * $fields = array()
     */
    private static $fields = [];


    /**
     * This function evaluates/constructs
     * the $options variable passed in.
     *
     * @param $options
     * @return mixed
     */
    private static function evalOptions($options)
    {
        $attr = "";
        foreach ($options as $index => $option) {
            $index = preg_replace('/ +/', '', $index);
            $attr .= " $index='$option'";
        }

        return empty($attr) ? '' : ' '.trim($attr, ' ');
    }


    /**
     * Construct a form with a bound model to automatically
     * populate a form input with the same property to the model's collection.
     *
     * @param $model
     * @param null $route
     * @param array $options
     * @return string
     * @throws Exception
     */
    public static function bind($model, $route = null, $options = [])
    {
        if (!is_object($model)) {
            throw new Exception("Argument supplied is not an object type");
        }

        self::$fields = $model;

        $opts = self::evalOptions($options);
        $route = ($route == null) ? $_SERVER['REQUEST_URI'] : $route;
        $method = (preg_match('/method\=/i', $opts)) ? "" : "method='POST'";

        return "<form action='{$route}' {$method}{$opts} accept-charset='UTF-8'>\n
                <input type='hidden' name='__FORM_TOKEN__' value='" . Token::create() . "'>";
    }


    /**
     * Construct a form.
     *
     * @param null $route
     * @param array $options
     * @return string
     */
    public static function open($route = null, $options = [])
    {
        $opts = self::evalOptions($options);
        $route = ($route == null) ? $_SERVER['REQUEST_URI'] : $route;
        $method = (preg_match('/method\=/i', $opts)) ? "" : "method='POST' ";

        return "<form action='{$route}' {$method}{$opts} accept-charset='UTF-8'>\n
                <input type='hidden' name='__FORM_TOKEN__' value='" . Token::create() . "'>";
    }


    /**
     * Close the form and destroy all
     * session resources by default.
     *
     * @param bool $destroyFieldsAndError
     * @return string
     */
    public static function close($destroyFieldsAndError = true)
    {
        self::$fields = [];
        if ($destroyFieldsAndError) {
            unset($_SESSION['__ERRORS__']);
            unset($_SESSION['__FIELDS__']);
        }

        return "</form>\n";
    }


    /**
     * Returns a text form input
     *
     * @param string $name
     * @param null $value
     * @param array $options
     * @return string
     */
    public static function text($name, $value = null, $options = [])
    {
        $opts = self::evalOptions($options);
        $field = (!empty(self::$fields)) ? " value='" . self::$fields->$name . "'" : '';
        $value = (!is_null($value)) ? " value='" . $value . "'" : $field;

        return "<input type='text' name='{$name}' {$value} {$opts}>\n";
    }


    /**
     * Returns a password form input
     * this does'nt have to have a value
     * for security reasons.
     *
     * @param $name
     * @param null $value
     * @param array $options
     * @return string
     */
    public static function password($name, $value = null, $options = [])
    {
        $opts = self::evalOptions($options);
        $value = (!is_null($value)) ? " value='" . $value . "'" : '';

        return "<input type='password' name='{$name}' {$value} {$opts}>\n";
    }


    /**
     * Returns a textarea form input
     *
     * @param $name
     * @param null $value
     * @param array $options
     * @return string
     */
    public static function textarea($name, $value = null, $options = [])
    {
        $opts = self::evalOptions($options);
        $field = (!empty(self::$fields)) ? self::$fields->$name : '';
        $value = (!is_null($value)) ? $value : $field;

        return "<textarea name='{$name}' {$opts}>{$value}</textarea>\n";
    }


    /**
     * Returns an email form input
     *
     * @param $name
     * @param null $value
     * @param array $options
     * @return string
     */
    public static function email($name, $value = null, $options = [])
    {
        $opts = self::evalOptions($options);
        $field = (!empty(self::$fields)) ? " value='" . self::$fields->$name . "'" : '';
        $value = (!is_null($value)) ? " value='" . $value . "'" : $field;

        return "<input type='email' name='{$name}' {$value} {$opts}>\n";
    }


    /**
     * Returns a number form input
     *
     * @param $name
     * @param null $value
     * @param array $options
     * @return string
     */
    public static function number($name, $value = null, $options = [])
    {
        $opts = self::evalOptions($options);
        $field = (!empty(self::$fields)) ? " value='" . self::$fields->$name . "'" : '';
        $value = (!is_null($value)) ? " value='" . $value . "'" : $field;

        return "<input type='number' name='{$name}' {$value} {$opts}>\n";
    }


    /**
     * Returns a date form input
     *
     * @param $name
     * @param null $value
     * @param array $options
     * @return string
     */
    public static function date($name, $value = null, $options = [])
    {
        $opts = self::evalOptions($options);
        $field = (!empty(self::$fields)) ? " value='" . self::$fields->$name . "'" : '';
        $value = (!is_null($value)) ? " value='" . $value . "'" : $field;

        return "<input type='date' name='{$name}' {$value} {$opts}>\n";
    }



    /**
     * Returns a checkbox form input
     *
     * @param $name
     * @param null $value
     * @param array $options
     * @return string
     */
    public static function checkbox($name, $value = null, $options = [])
    {
        $opts = self::evalOptions($options);
        $field = (!empty(self::$fields)) ? self::$fields->$name : '';
        $value = (!is_null($value)) ? " value='" . $value . "'" : $field;

        return "<input type='checkbox' name='{$name}' {$value} {$opts}>";
    }


    /**
     * Returns a checkbox form input
     *
     * @param $name
     * @param null $value
     * @param array $options
     * @return string
     */
    public static function radio($name, $value = null, $options = [])
    {
        $opts = self::evalOptions($options);
        $field = (!empty(self::$fields)) ? self::$fields->$name : '';
        $value = (!is_null($value)) ? " value='" . $value . "'" : $field;

        return "<input type='radio' name='{$name}' {$value} {$opts}>";
    }


    /**
     * Returns a hidden form input
     *
     * @param $name
     * @param null $value
     * @param array $options
     * @return string
     */
    public static function hidden($name, $value = null, $options = [])
    {
        $opts = self::evalOptions($options);
        $field = (!empty(self::$fields)) ? " value='" . self::$fields->$name . "'" : '';
        $value = (!empty($value)) ? " value='" . $value . "'" : $field;

        return "<input type='hidden' name='{$name}' {$value} {$opts}>\n";
    }


    /**
     * Returns a file form input
     *
     * @param $name
     * @param null $value
     * @param array $options
     * @return string
     */
    public static function file($name, $value = null, $options = [])
    {
        $opts = self::evalOptions($options);
        $field = (!empty(self::$fields)) ? " value='" . self::$fields->$name . "'" : '';
        $value = (!is_null($value)) ? " value='" . $value . "'" : $field;

        return "<input{$value} type='file' name='{$name}' {$opts}>\n";
    }


    /**
     * Returns a submit button element
     *
     * @param $name
     * @param null $value
     * @param array $options
     * @return string
     */
    public static function submit($name, $value = null, $options = [])
    {
        $opts = self::evalOptions($options);
        $value = !empty($value) ? " value='" . $value . "'" : '';

        return "<input type='submit' name='{$name}' {$value} {$opts}>\n";
    }


    /**
     * Returns a button element
     *
     * @param $name
     * @param null $value
     * @param array $options
     * @return string
     */
    public static function button($name, $value = null, $options = [])
    {
        $opts = self::evalOptions($options);
        $value = is_null($value) ? $name : $value;

        return "<button name='{$name}' {$opts}>{$value}</button>\n";
    }


    /**
     * Returns a Select element that can detect a specified value
     * to be on top and default option selected.
     *
     * @param $name
     * @param null $value
     * @param array $optionValues
     * @param array $options
     * @return string
     */
    public static function select($name, $value = null, $optionValues = [], $options = [])
    {
        $opts = self::evalOptions($options);
        $value = (!empty(self::$fields)) ? self::$fields->$name : $value;

        if (!empty($value)) {
            if (is_array($value)) {
                $value = "<option value='" . array_keys($value)[0] . "'>" . array_values($value)[0] . "</option>\n";
            } else {
                $value = "<option>$value</option>\n";
            }
        }

        foreach ($optionValues as $index => $optionValue) {
            $value .= "<option value='{$index}'>{$optionValue}</option>\n";
        }

        return "<select name='{$name}' {$opts}>{$value}</select>\n";
    }
}