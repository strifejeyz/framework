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
    private static function evaluate($options)
    {
        $attr = "";
        foreach ($options as $index => $option) {
            $index = preg_replace('/ +/', '', $index);
            $attr .= " $index='$option'";
        }

        return empty($attr) ? '' : ' ' . trim($attr, ' ');
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
        $opts = self::evaluate($options);
        self::$fields = $model;

        if (preg_match('/method\=/i', $opts)):
            $method = null;
        else:
            $method = "method='POST' ";
        endif;

        $token = Token::create();
        $origin = uri();
        return <<<EOF
            <form action='$route' {$method}{$opts} accept-charset='UTF-8'>
                <input type='hidden' name='__FORM_TOKEN__' value='$token'>
                <input type='hidden' name='__FORM_ORIGIN__' value='$origin'>
EOF;
    }


    /**
     * Construct a form.
     *
     * @param $route
     * @param array $options
     * @return string
     */
    public static function open($route, $options = [])
    {
        $opts = self::evaluate($options);
        if (preg_match('/method\=/i', $opts)):
            $method = null;
        else:
            $method = "method='POST' ";
        endif;

        $token = Token::create();
        $origin = uri();
        return <<<EOF
            <form action='$route' {$method}{$opts} accept-charset='UTF-8'>
                <input type='hidden' name='__FORM_TOKEN__' value='$token'>
                <input type='hidden' name='__FORM_ORIGIN__' value='$origin'>
EOF;
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
        $opts = self::evaluate($options);

        if (is_null($value)):
            if (!empty(self::$fields)):
                $value = "value='" . self::$fields->$name . "'";
            elseif (isset($_SESSION['__FIELDS__']) AND isset($_SESSION['__FIELDS__'][$name])):
                $value = "value='" . $_SESSION['__FIELDS__'][$name] . "'";
            endif;
        else:
            $value = "value='$value'";
        endif;

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
        $opts = self::evaluate($options);
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
        $opts = self::evaluate($options);

        if (is_null($value)):
            if (!empty(self::$fields)):
                $value = self::$fields->$name;
            elseif (isset($_SESSION['__FIELDS__']) AND isset($_SESSION['__FIELDS__'][$name])):
                $value = $_SESSION['__FIELDS__'][$name];
            endif;
        endif;

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
        $opts = self::evaluate($options);

        if (is_null($value)):
            if (!empty(self::$fields)):
                $value = "value='" . self::$fields->$name . "'";
            elseif (isset($_SESSION['__FIELDS__']) AND isset($_SESSION['__FIELDS__'][$name])):
                $value = "value='" . $_SESSION['__FIELDS__'][$name] . "'";
            endif;
        else:
            $value = "value='$value'";
        endif;

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
        $opts = self::evaluate($options);

        if (is_null($value)):
            if (!empty(self::$fields)):
                $value = "value='" . self::$fields->$name . "'";
            elseif (isset($_SESSION['__FIELDS__']) AND isset($_SESSION['__FIELDS__'][$name])):
                $value = "value='" . $_SESSION['__FIELDS__'][$name] . "'";
            endif;
        else:
            $value = "value='$value'";
        endif;

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
        $opts = self::evaluate($options);

        if (is_null($value)):
            if (!empty(self::$fields)):
                $value = "value='" . self::$fields->$name . "'";
            elseif (isset($_SESSION['__FIELDS__']) AND isset($_SESSION['__FIELDS__'][$name])):
                $value = "value='" . $_SESSION['__FIELDS__'][$name] . "'";
            endif;
        else:
            $value = "value='$value'";
        endif;

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
        $opts = self::evaluate($options);

        if (is_null($value)):
            if (!empty(self::$fields)):
                $value = "value='" . self::$fields->$name . "'";
            elseif (isset($_SESSION['__FIELDS__']) AND isset($_SESSION['__FIELDS__'][$name])):
                $value = "value='" . $_SESSION['__FIELDS__'][$name] . "'";
            endif;
        else:
            $value = "value='$value'";
        endif;

        if (!empty($value)):
            $checked = "checked='checked'";
        else:
            $checked = "";
        endif;

        return "<input type='checkbox' {$checked} name='{$name}' {$value} {$opts}>";
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
        $opts = self::evaluate($options);

        if (is_null($value)):
            if (!empty(self::$fields)):
                $value = "value='" . self::$fields->$name . "'";
            elseif (isset($_SESSION['__FIELDS__']) AND isset($_SESSION['__FIELDS__'][$name])):
                $value = "value='" . $_SESSION['__FIELDS__'][$name] . "'";
            endif;
        else:
            $value = "value='$value'";
        endif;

        if (!empty($value)):
            $checked = "checked='checked'";
        else:
            $checked = "";
        endif;

        return "<input type='radio' {$checked} name='{$name}' {$value} {$opts}>";
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
        $opts = self::evaluate($options);

        if (is_null($value)):
            if (!empty(self::$fields)):
                $value = "value='" . self::$fields->$name . "'";
            elseif (isset($_SESSION['__FIELDS__']) AND isset($_SESSION['__FIELDS__'][$name])):
                $value = "value='" . $_SESSION['__FIELDS__'][$name] . "'";
            endif;
        else:
            $value = "value='$value'";
        endif;

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
        $opts = self::evaluate($options);

        if (is_null($value)):
            if (!empty(self::$fields)):
                $value = "value='" . self::$fields->$name . "'";
            elseif (isset($_SESSION['__FIELDS__']) AND isset($_SESSION['__FIELDS__'][$name])):
                $value = "value='" . $_SESSION['__FIELDS__'][$name] . "'";
            endif;
        else:
            $value = "value='$value'";
        endif;

        return "<input {$value} type='file' name='{$name}' {$opts}>\n";
    }


    /**
     * Returns a submit button element
     *
     * @param $name
     * @param null $value
     * @param array $options
     * @return string
     */
    public static function button($name, $value = null, $options = [])
    {
        $opts = self::evaluate($options);
        if (is_null($value)):
            if (!empty(self::$fields)):
                $value = "value='" . self::$fields->$name . "'";
            elseif (isset($_SESSION['__FIELDS__']) AND isset($_SESSION['__FIELDS__'][$name])):
                $value = "value='" . $_SESSION['__FIELDS__'][$name] . "'";
            endif;
        else:
            $value = "value='$value'";
        endif;

        return "<input type='submit' name='{$name}' {$value} {$opts}>\n";
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
    public static function select($name, $value = null, $optionValues = array(), $options = [])
    {
        $opts = self::evaluate($options);

        if (is_null($value)):
            if (!empty(self::$fields)):
                $value = "<option value='" . ucwords(self::$fields->$name) . "'>" . self::$fields->$name . "</option>\n";
            elseif (isset($_SESSION['__FIELDS__']) AND isset($_SESSION['__FIELDS__'][$name])):
                $value = "<option value='" . $_SESSION['__FIELDS__'][$name] . "'>" . ucwords($_SESSION['__FIELDS__'][$name]) . "</option>\n";
            else:
                $value = null;
            endif;
        else:
            if (!is_array($value)):
                $value = "<option value='$value'>" . ucwords($value) . "</option>\n";
            else:
                $value = "<option value='" . array_keys($value)[0] . "'>" . ucwords(array_values($value)[0]) . "</option>\n";
            endif;
        endif;

        $arr_values = array_values($optionValues);
        foreach (array_keys($optionValues) as $index => $op):
            $value .= "<option value='" . $op . "'>" . $arr_values[$index] . "</option>\n";
        endforeach;

        return "<select name='{$name}' {$opts}>\n{$value}</select>\n";
    }
}