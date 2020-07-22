<?php

namespace Vongola\ColorHash;

use RuntimeException;
use InvalidArgumentException;

class Color
{
    private $hasher;
    private $options;
    const HUE_KEY = 'hue';
    const SATURATION_KEY = 'saturation';
    const LIGHTNESS_KEY = 'lightness';

    /**
     * ColorHash constructor.
     */
    public function __construct()
    {
        $this->hasher = 'bkdr';
        $this->options = [
            static::HUE_KEY => [['min' => 0, 'max' => 360]],
            self::SATURATION_KEY => [0.35, 0.5, 0.65],
            self::LIGHTNESS_KEY => [0.35, 0.5, 0.65],
        ];
    }

    /**
     * @param $options
     * @return Color
     */
    public function custom($options = [])
    {
        // Custom hash function
        if (array_key_exists('hash', $options)) {
            $this->customHash($options['hash']);
        }

        // Custom Hue
        if (array_key_exists(self::HUE_KEY, $options)) {
            $this->customHue($options[self::HUE_KEY]);
        }

        // Custom saturation
        if (array_key_exists(self::SATURATION_KEY, $options)) {
            $this->customSaturation($options[self::SATURATION_KEY]);
        }

        // Custom lightness
        if (array_key_exists(self::LIGHTNESS_KEY, $options)) {
            $this->customLightness($options[self::LIGHTNESS_KEY]);
        }

        return $this;
    }

    /**
     * Custom Hash
     *
     * @param string|callable $hasher
     * @return Color
     */
    public function customHash($hasher)
    {
        $this->hasher = $hasher;
        return $this;
    }

    /**
     * Custom Hue
     *
     * @param int|array $hue
     * @return Color
     */
    public function customHue($hue)
    {
        $failed = false;
        $newHue = [];
        if (is_numeric($hue)) {
            array_push($newHue, ['min' => $hue, 'max' => $hue]);
        } elseif (is_array($hue) && array_key_exists('min', $hue) && array_key_exists('max', $hue)) {
            array_push($newHue, ['min' => $hue['min'], 'max' => $hue['max']]);
        } elseif (!empty($hue) && is_array($hue[0])) {
            $newHue = $hue;
        } else {
            $failed = true;
        }
        $this->options[self::HUE_KEY] = [];
        foreach ($newHue as $element) {
            if (array_key_exists('min', $element) && array_key_exists('max', $element)
                && Util::isValidHue($element['min']) && Util::isValidHue($element['max'])) {
                array_push($this->options[self::HUE_KEY], ['min' => $element['min'], 'max' => $element['max']]);    
            }
        }
        if (empty($this->options[self::HUE_KEY])) {
            $failed = true;
        }
        if ($failed) {
            throw new InvalidArgumentException('Wrong argument in custom hue.');
        }
        return $this;
    }

    /**
     * Custom saturation
     *
     * @param float|array $saturation
     * @return Color
     */
    public function customSaturation($saturation)
    {
        $failed = false;
        if (is_float($saturation) && Util::isValidSaturation($saturation)) {
            $this->options[self::SATURATION_KEY] = [$saturation];
        } elseif (is_array($saturation)) {
            $this->options[self::SATURATION_KEY] = [];
            foreach ($saturation as $element) {
                if (is_float($element) && Util::isValidSaturation($element)) {
                    array_push($this->options[self::SATURATION_KEY], $element);
                } else {
                    // Ignore
                }
            }
            if (empty($this->options[self::SATURATION_KEY])) {
                $failed = true;
            }
        } else {
            $failed = true;
        }
        if ($failed) {
            throw new InvalidArgumentException('Wrong argument in custom saturation.');
        }
        return $this;
    }

    /**
     * Custom Lightness
     *
     * @param float|array $lightness
     * @return Color
     */
    public function customLightness($lightness)
    {
        $failed = false;
        if (is_float($lightness) && Util::isValidLightness($lightness)) {
            $this->options[self::LIGHTNESS_KEY] = [$lightness];
        } elseif (is_array($lightness)) {
            $this->options[self::LIGHTNESS_KEY] = [];
            foreach ($lightness as $element) {
                if (is_float($element) && Util::isValidLightness($element)) {
                    array_push($this->options[self::LIGHTNESS_KEY], $element);
                } else {
                    // Ignore
                }
            }
            if (empty($this->options[self::LIGHTNESS_KEY])) {
                $failed = true;
            }
        } else {
            $failed = true;
        }
        if ($failed) {
            throw new InvalidArgumentException('Wrong argument in custom lightness.');
        }
        return $this;
    }

    /**
     * Hash string
     *
     * @param string $string
     * @return string
     */
    private function hash(string $string)
    {
        if (is_string($this->hasher)) {
            if (method_exists('Hasher', $this->hasher)) {
                $hash = call_user_func(['Hasher', $this->hasher], $string);
            } else {
                $hash = Hasher::bkdr($string);
            }
        } elseif (is_callable($this->hasher)) {
            $hash = call_user_func($this->hasher, $string);
        } else {
            throw new InvalidArgumentException('Can not execute hash function.');
        }
        return $hash;
    }

    /**
     * Convert a string to a hsl array
     * Note that H ∈ [0, 360); S ∈ [0, 1]; L ∈ [0, 1];
     * @param $string
     * @return array
     */
    public function hsl($string)
    {
        $hash = $this->hash($string);
        // Hue
        $range = $this->options[self::HUE_KEY][$hash % count($this->options[self::HUE_KEY])];
        $hueResolution = "727";
        $hue = intval((($hash / count($this->options[self::HUE_KEY])) % $hueResolution)
               * ($range['max'] - $range['min']) / $hueResolution + $range['min']);
        // Saturation
        $hash = intval($hash / 360);
        $saturation = $this->options[self::SATURATION_KEY][$hash % count($this->options[self::SATURATION_KEY])];
        // Lightness
        $hash = intval($hash / count($this->options[self::LIGHTNESS_KEY]));
        $lightness = $this->options[self::LIGHTNESS_KEY][$hash % count($this->options[self::LIGHTNESS_KEY])];
        
        return [$hue, $saturation, $lightness];
    }

    /**
     * Convert a string to a rgb array
     * @param $string
     * @return array
     */
    public function rgb($string)
    {
        $hsl = $this->hsl($string);
        return Util::hsl2rgb($hsl);
    }

    /**
     * Convert a string to color hex
     * @param $string
     * @return string
     */
    public function hex($string)
    {
        $rgb = $this->rgb($string);
        return Util::rgb2hex($rgb);
    }
}
