<?php namespace Kernel;

/**
 * Class Errors
 * made for custom Error handling
 *
 * @property $filename
 * @property $message
 * @property $error_type
 */
class Errors
{
    /**
     * Method trigger
     * Setting up params to be available
     * for the 3 methods below.
     * 0 = warning (script continues)
     * 1 = user error(stops the script)
     * 2 = fatal (stops the script)
     *
     * @param $filename
     * @param $message
     * @param int $error_type
     */
    static function trigger($filename, $message, $error_type = 0)
    {
        if ($error_type == 0) {
            $e_type = "Warning";
        } else if ($error_type == 1) {
            $e_type = "User Error";
        } else {
            $e_type = "Fatal Error";
        }

        $message = "
        <table border='1'>
            <tr>
                <th colspan='2'><b>Error:</b> $e_type</th>
            </tr>
            <tr>
                <td>Error in File:</td>
                <td>$filename</td>
            </tr>
            <tr>
                <td>Message:</td>
                <td>$message</td>
            </tr>
        </table>";

       if ($error_type == 0) {
           print($message);
       } else {
           return die($message);
       }
    }
}