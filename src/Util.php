<?php

namespace Vongola\ColorHash;

class Util
{
    /**
     * Check numeric is between max value and max value
     *
     * @param mixed $target
     * @param float|int $min
     * @param float|int $max
     * @return bool
     */
    public static function isBetween($target, $min, $max): bool
    {
        return $target >= $min && $target <= $max;
    }

    /**
     * Check Hue value is between 0 - 360
     *
     * @param mixed $hue
     * @return bool
     */
    public static function isValidHue($hue): bool
    {
        return is_numeric($hue) && self::isBetween($hue, 0, 360);
    }

    /**
     * Check Saturation value is between 0 - 1
     *
     * @param mixed $saturation
     * @return bool
     */
    public static function isValidSaturation($saturation): bool
    {
        return is_numeric($saturation) && self::isBetween($saturation, 0, 1);
    }

    /**
     * Check Lightness value is between 0 - 1
     *
     * @param mixed $lightness
     * @return bool
     */
    public static function isValidLightness($lightness): bool
    {
        return is_numeric($lightness) && self::isBetween($lightness, 0, 1);
    }

    /**
     * Convert HSL to RGB
     *
     * @see {@link http://zh.wikipedia.org/wiki/HSL和HSV色彩空间} for further information.
     * @param array $hsl
     * @return array
     */
    public static function hsl2rgb($hsl): array
    {
        [$hue, $saturation , $lightness] = $hsl;
        $rgb = [];
        if ($saturation === 0) {
            $rgb = [$lightness, $lightness, $lightness];
        } else {
            if ($lightness >= 0.5) {
                $q = $lightness + $saturation - ($lightness * $saturation);
            } else {
                $q = $lightness * (1 + $saturation);
            }
            $p = 2 * $lightness - $q;
            $hK = $hue / 360;
            $rgb = array_map(function ($tC) use ($p, $q) {
                if ($tC < 0) {
                    $tC += 1;
                } elseif ($tC > 1) {
                    $tC -= 1;
                } else {
                    // Do nothing
                }
                return Util::calcFinalColor($tC, $p, $q);
            }, [$hK + (1 / 3), $hK, $hK - (1 / 3)]);
        }
        return $rgb;
    }

    /**
     * Calc final RGB color
     *
     * @param float|int $tC
     * @param float|int $p
     * @param float|int $q
     * @return int
     */
    public static function calcFinalColor($tC, $p, $q): int
    {
        $color = 0;
        if ($tC < (1 / 6)) {
            $color = $p + (($q - $p) * 6 * $tC);
        } elseif ($tC < (1 / 2)) {
            $color = $q;
        } elseif ($tC < (2 / 3)) {
            $color = $p + (($q - $p) * 6 * ((2 / 3) - $tC));
        } else {
            $color = $p;
        }
        return intval(round($color * 255));
    }
    
    /**
     * Convert RGB to HEX
     *
     * @param array $rgb
     * @return string
     */
    public static function rgb2hex($rgb)
    {
        return sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
    }
}
