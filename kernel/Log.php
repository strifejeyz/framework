<?php namespace Kernel;

/**
 * Class Log
 *
 * @package Kernel
 */
class Log
{
    /**
     * log constructor
     *
     * @param $message
     * @param string $file
     * @return self
     */
    public function __construct($message, $file = 'logs.txt')
    {
        $file = fopen(storage_dir() . "logs/" . $file, 'a');

        if (fwrite($file, $message . "\n")) {
            $result = true;
        } else {
            $result = false;
        }

        return ($result);
    }
}