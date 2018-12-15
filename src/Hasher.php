<?php

namespace Vongola\ColorHash;

class Hasher
{
    /**
     * @param $string
     * @param null|\Closure $hashFunc
     * @return bool|string
     */
    public function hash($string, $hashFunc = null)
    {
        if ($hashFunc === null) {
            return $this->_hash($string);
        } else {
            return $hashFunc($string);
        }
    }

    /**
     * @param $string
     * @return bool|string
     */
    private function _hash($string)
    {
        $seed = "131";
        $seed2 = "137";
        $hash = "0";
        // make hash more sensitive for short string like 'a', 'b', 'c'
        $string .= 'x';
        $safeInteger = Util::parseInt(bcdiv("9007199254740991", $seed2));
        for ($i = 0; $i < mb_strlen($string); $i++) {
            // bccomp return 0 when equal, return 1 when $left>$right, and return -1 when $right > $left
            if (bccomp($hash, $safeInteger) === 1) {
                $hash = Util::parseInt(bcdiv($hash, $seed2));
            }
            $hash = bcadd(bcmul($hash, $seed), strval($this->getCharCode(mb_substr($string, $i, 1, 'UTF-8'))));
        }
        return $hash;
    }

    private function getCharCode($char) {
        list(, $ord) = unpack('N', mb_convert_encoding($char, 'UCS-4BE', 'UTF-8'));
        return $ord;
    }

}
