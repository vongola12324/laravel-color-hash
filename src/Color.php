<?php

namespace Vongola\ColorHash;

class Color
{
    private $hasher, $customHue, $customLightness, $customSaturation;


    /**
     * ColorHash constructor.
     */
    public function __construct()
    {
        $this->hasher = new Hasher();
        $this->customHue = [];
        $this->customLightness = [0.35, 0.5, 0.65];
        $this->customSaturation = [0.35, 0.5, 0.65];
    }

    /**
     * @param $options
     * @return Color
     */
    public function custom($options = [])
    {
        // Custom lightness
        if (array_key_exists('lightness', $options)) {
            $L = $options['lightness'] || [0.35, 0.5, 0.65];
            $this->customLightness = is_array($L) ? $L : [$L];
        }

        // Custom saturation
        if (array_key_exists('saturation', $options)) {
            $S = $options['saturation'] || [0.35, 0.5, 0.65];
            $this->customSaturation = is_array($S) ? $S : [$S];
        }

        // Custom Hue
        if (array_key_exists('hue', $options)) {
            $H = $options['hue'];
            if (is_numeric($H)) {
                $this->customHue = ['min' => $H, 'max' => $H];
            } else {
                $this->customHue = $H;
            }
        }
        if (!empty($this->customHue)) {
            $this->customHue = array_map(function ($range) {
                return [
                    'min' => array_key_exists('min', $range) ? $range['min'] : 0,
                    'max' => array_key_exists('max', $range) ? $range['max'] : 0
                ];
            }, $this->customHue);
        }

        return $this;
    }

    /**
     * Convert a string to a hsl array
     * @param $string
     * @return
     */
    public function hsl($string)
    {
        $hash = $this->hasher->hash($string);
        if (!empty($this->customHue)) {
            $range = $this->customHue[intval(bcmod($hash, strval(count($this->customHue))))];
            $hueResolution = "727";
            $H = bcadd(bcdiv(bcmul(bcmod(bcdiv($hash, strval(count($this->customHue))), $hueResolution), strval($range['max'] - $range['min'])), $hueResolution), strval($range['min']));
        } else {
            $H = bcmod($hash, "359");
        }
        $H = intval($H);
        $hash = Util::parseInt(bcdiv($hash, "360"));
        $S = $this->customSaturation[intval(bcmod($hash, strval(count($this->customSaturation))))];
        $hash = Util::parseInt(bcdiv($hash, strval(count($this->customSaturation))));
        $L = $this->customLightness[intval(bcmod($hash, strval(count($this->customLightness))))];

        return [$H, $S, $L];
    }

    /**
     * Convert a string to a rgb array
     * @param $string
     * @return
     */
    public function rgb($string)
    {
        $hsl = $this->hsl($string);
        return $this->hsl2rgb($hsl);
    }

    /**
     * Convert a string to color hex
     * @param $string
     * @return
     */
    public function hex($string)
    {
        $rgb = $this->rgb($string);
        return $this->rgb2hex($rgb);
    }

    /**
     * Convert HSL to RGB
     * @see {@link http://zh.wikipedia.org/wiki/HSL和HSV色彩空间} for further information.
     * @param $hsl
     * @return array
     */
    private function hsl2rgb($hsl)
    {
        $H = $hsl[0] / 360;
        $S = $hsl[1];
        $L = $hsl[2];
        $q = $L < 0.5 ? $L * (1 + $S) : $L + $S - $L * $S;
        $p = 2 * $L - $q;
        return array_map(function ($color) use ($p, $q) {
            if ($color < 0) {
                $color++;
            }
            if ($color > 1) {
                $color--;
            }
            if ($color < 1 / 6) {
                $color = $p + ($q - $p) * 6 * $color;
            } else if ($color < 0.5) {
                $color = $q;
            } else if ($color < 2 / 3) {
                $color = $p + ($q - $p) * 6 * (2 / 3 - $color);
            } else {
                $color = $p;
            }
            return round($color * 255);
        }, [$H + 1 / 3, $H, $H - 1 / 3]);
    }

    /**
     * Convert RGB to hex
     * @param $rgb
     * @return string
     */
    private function rgb2hex($rgb)
    {
        return sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
    }
}
