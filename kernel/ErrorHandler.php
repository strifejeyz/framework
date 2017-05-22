<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 5/2/2017
 * Time: 7:12 PM
 */
class ErrorHandler extends Exception
{
    /**
     * Returns custom error message.
     *
     * @method message
     * @return string
     */
    public function message()
    {
        $message = "<pre>Error on line <b>{$this->getLine()}</b> in <b>{$this->getFile()}</b> {$this->getMessage()}<pre>";

        return ($message);
    }
}