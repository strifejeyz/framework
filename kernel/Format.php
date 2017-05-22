<?php namespace Kernel;

/**
 * Class Formatter
 *
 * @package Kernel
 */
class Format
{
    /**
     * Fold or cuts the original to a given length.
     * strips any image tags to avoid clutters.
     *
     * @param $text
     * @param $length
     * @param $postfix
     * @return string
     */
    static function fold($text, $length, $postfix = true)
    {
        $period = "";

        if ($postfix) {
            if (strlen($text) > $length) {
                $period = "...";
            }
        }

        $text = substr($text, 0, $length);
        return strip_tags($text) . $period;
    }


    /**
     * Return contents of a variable
     *
     * @param $var
     * @return mixed
     */
    static function dump($var)
    {
        echo '<pre style="color: #aa104A">' . var_export($var, TRUE) . '</pre>';
    }


    /**
     * Return a slugged string. Strips any illegal characters and
     * symbols and converts it into a readable slug url.
     *
     * @param $text
     * @param $delimiter
     * @return string
     */
    static function slug($text, $delimiter = '-')
    {
        $slug = preg_replace('/[^A-Za-z0-9\-]/', ' ', $text);
        $slug = str_replace(' ', $delimiter, $slug);
        $slug = preg_replace('/-+/', '-', $slug);

        return strtolower(trim($slug, '-'));
    }


    /**
     * Returns a sanitized string
     *
     * @param $text
     * @return string
     */

    static function sanitize($text)
    {
        $text = filter_var(htmlentities(strip_tags($text)), FILTER_SANITIZE_STRING);
        return ($text);
    }



    /**
     * Returns a reversed string
     *
     * @param $text
     * @return string
     */

    static function reverse($text)
    {
        $string = "";
        foreach (array_reverse(str_split($text, count($text))) as $c) {
            $string .= $c;
        }

        return ($string);
    }



    /**
     * Get the number of days
     * between two dates
     *
     * @param $date1
     * @param $date2
     * @return mixed
     */
    static function dateDifference($date1, $date2)
    {
        $date_entry = new \DateTime($date1);
        $dateNow = new \DateTime($date2);

        return $dateNow->diff($date_entry)->format("%a");
    }


    /**
     * concatenates array values into one
     * set lengthy of string
     *
     * @param array $values
     * @param $delimiter
     * @return mixed
     */
    static function arrayConcat(array $values, $delimiter = '')
    {
        $string = "";
        $delimiter = (!empty($delimiter)) ? $delimiter : '';

        for ($i = 0; $i < count($values); $i++) {
            $value = array_values($values)[$i];
            $string .= "{$value}{$delimiter}";
        }

        return ($string);
    }


    /**
     * Lowercase a string
     *
     * @param $string
     * @return string
     */
    public static function lowercase($string)
    {
        return strtolower($string);
    }



    /**
     * Uppercase a string
     *
     * @param $string
     * @return string
     */
    public static function uppercase($string)
    {
        return strtoupper($string);
    }


    /**
     * Capitalize a string
     *
     * @param $string
     * @return string
     */
    public static function capitalize($string)
    {
        return ucfirst($string);
    }


    /**
     * Capitalize each words of a string
     *
     * @param $string
     * @return string
     */
    public static function capitalizeWords($string)
    {
        return ucwords($string);
    }


    /**
     * Count words on a string
     *
     * @param $string
     * @return string
     */
    public static function wordCount($string)
    {
        return str_word_count($string);
    }
}

