<?php

namespace Vongola\ColorHash;

class Hasher
{
    /**
     * BKDR Hash (modified version matching zenozeng/color-hash)
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
        $seed2 = 137;
        $hash = 0;
        $input .= 'x';
        // Use JS Number.MAX_SAFE_INTEGER to match zenozeng/color-hash behavior
        $maxSafeInt = intdiv(9007199254740991, $seed2);
        foreach (str_split($input) as $char) {
            if ($hash > $maxSafeInt) {
                $hash = intdiv($hash, $seed2);
            }
            $hash = $hash * $seed + ord($char);
        }
        return $hash;
    }

    /**
     * SHA-256 Hash — first 8 hex digits parsed as unsigned int
     *
     * @param  string $input
     * @return int
     */
    public static function sha256(string $input): int
    {
        return (int) hexdec(substr(hash('sha256', $input), 0, 8));
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
