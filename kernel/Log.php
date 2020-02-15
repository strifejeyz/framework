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
     * @param string $fileName
     */
    public function __construct($message, $fileName = 'logs.txt')
    {
        $file = fopen(STORAGE_PATH . "logs/" . $fileName, 'a');

        if (fwrite($file, $message . "\n")) {
            $result = true;
        } else {
            $result = false;
        }

        return ($result);
    }
}