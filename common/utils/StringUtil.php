<?php

namespace app\common\utils;

class StringUtil
{
    /**
     * Intercept the content after the first specified symbol
     *
     * @param string $string
     * @param string $cutString
     * @return bool|string
     * @author: lirong
     */
    public static function cutOutLater(string $string, string $cutString): string
    {
        if (strpos($string, $cutString) !== 0) {
            return substr($string, strpos($string, $cutString) + strlen($cutString));
        }
        return $string;
    }

    /**
     * Intercept the content before the first specified symbol
     *
     * @param string $string
     * @param string $cutString
     * @return bool|string
     * @author: lirong
     */
    public static function cutOutFormer(string $string, string $cutString)
    {
        if (strpos($string, $cutString) !== 0) {
            return substr($string, 0, strpos($string, $cutString));
        }
        return $string;
    }
}