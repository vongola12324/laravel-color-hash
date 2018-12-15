<?php
/**
 * Created by PhpStorm.
 * User: Vongola
 * Date: 2018/12/15
 * Time: 下午 08:31
 */

namespace Vongola\ColorHash;


class Util
{
    public static function parseInt($string) {
        $pos = strpos($string, '.');
        if ($pos === false) {
            return $string;
        } else {
            return substr($string, 0, $pos);
        }
    }
}