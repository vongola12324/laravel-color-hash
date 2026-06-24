<?php

namespace Vongola\ColorHash;

class Hasher
{
    /**
     * BKDR Hash
     *
     * @param  string $input
     * @return int
     */
    public static function BKDRHash(string $input): int
    {
        return self::bkdr($input);
    }
    public static function bkdr(string $input): int
    {
        $seed = 131;
        $hash = 0;
        foreach (str_split($input) as $char) {
            $hash = ($hash * $seed + ord($char)) & 0x7FFFFFFF;
        }
        return $hash;
    }

    /**
     * AP Hash
     *
     * @param  string $input
     * @return int
     */
    public static function APHash(string $input): int
    {
        return self::ap($input);
    }
    public static function ap(string $input): int
    {
        $hash = 0;
        $i = 0;
        foreach (str_split($input) as $char) {
            if (($i & 1) === 0) {
                $hash ^= (($hash << 7) ^ ord($char) ^ ($hash >> 3));
            } else {
                $hash ^= (~(($hash << 11) ^ ord($char) ^ ($hash >> 5)));
            }
            $hash &= 0x7FFFFFFF;
            $i += 1;
        }
        return $hash;
    }

    /**
     * DJB Hash
     *
     * @param  string $input
     * @return int
     */
    public static function DJBHash(string $input): int
    {
        return self::djb($input);
    }
    public static function djb(string $input): int
    {
        $hash = 5381;
        foreach (str_split($input) as $char) {
            $hash = (($hash << 5) + ord($char)) & 0x7FFFFFFF;
        }
        return $hash;
    }

    /**
     * JS Hash
     *
     * @param  string $input
     * @return int
     */
    public static function JSHash(string $input): int
    {
        return self::js($input);
    }
    public static function js(string $input): int
    {
        $hash = 1315423911;
        foreach (str_split($input) as $char) {
            $hash = ($hash ^ (($hash << 5) + ord($char) + ($hash >> 2))) & 0x7FFFFFFF;
        }
        return $hash;
    }
}
