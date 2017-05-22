<?php namespace Kernel\Security;

/**
 * Class Hash
 *
 * @package Kernel
 */
class Hash
{
    /**
     * Returns a randomized pseudo
     * string value.
     *
     * @param $length
     * @return string
     **/
    public static function generateSalt($length = 50)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $string = "";

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, (strlen($characters) - 1))];
        }

        return $string;
    }


    /**
     * Hash the data passed in using hash_hmac with sha512
     * algorithm along with the application key as the third
     * parameter.
     *
     * @param $data
     * @return string
     **/
    public static function encode($data)
    {
        return hash_hmac('SHA512', $data, APPLICATION_KEY);
    }


    /**
     * Verify a given password to an existing
     * hashed password
     *
     * @param $stringData
     * @param $hashed
     * @return string
     **/
    public static function verify($stringData, $hashed)
    {
        return (self::encode($stringData) == $hashed) ? true : false;
    }


}