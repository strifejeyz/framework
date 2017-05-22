<?php namespace Kernel\Security;

/**
 * Class Encryption
 * @package Kernel
 */
class Encryption
{

    /**
     * In case you want to
     * use encryption to encoded/literal string.
     * use levels to strengthen encryption
     *
     * @param $data string
     * @param $levels int
     * @return string
     **/
    public static function encode($data, $levels = 1)
    {
        for($i = 0; $i < $levels; $i++) {
            $data = base64_encode(serialize($data));
        }

        return ($data);
    }


    /**
     * Decrypting encrypted value
     * It should match the encryption level
     * used on encrypting the data
     *
     * @param $data string
     * @param $levels int
     * @return string
     **/
    public static function decode($data, $levels = 1)
    {
        for($i = 0; $i < $levels; $i++) {
            $data = unserialize(base64_decode($data));
        }

        return ($data);
    }

}