<?php

namespace Vongola\ColorHash;

use RuntimeException;
use InvalidArgumentException;

class Color
{
    private $hasher;
    private $options;
    const hue_key = 'hue';
    const saturation_key = 'saturation';
    const lightness_key = 'lightness';

    /**
     * ColorHash constructor.
     */
    public function __construct()
    {
        $this->hasher = 'bkdr';
        $this->options = [
            static::hue_key => [['min' => 0, 'max' => 360]],
            self::saturation_key => [0.35, 0.5, 0.65],
            self::lightness_key => [0.35, 0.5, 0.65],
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
        if (array_key_exists(self::hue_key, $options)) {
            $this->customHue($options[self::hue_key]);
        }

        // Custom saturation
        if (array_key_exists(self::saturation_key, $options)) {
            $this->customSaturation($options[self::saturation_key]);
        }

        // Custom lightness
        if (array_key_exists(self::lightness_key, $options)) {
            $this->customLightness($options[self::lightness_key]);
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
        } elseif (count($hue) > 0 && is_array($hue[0])) {
            $newHue = $hue;
        } else {
            $failed = true;
        }
        $this->options[self::hue_key] = [];
        foreach ($newHue as $element) {
            if (array_key_exists('min', $element) && array_key_exists('max', $element)
                && Util::isValidHue($element['min']) && Util::isValidHue($element['max'])) {
                array_push($this->options[self::hue_key], ['min' => $element['min'], 'max' => $element['max']]);    
            }
        }
        if (empty($this->options[self::hue_key])) {
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
            $this->options[self::saturation_key] = [$saturation];
        } elseif (is_array($saturation)) {
            $this->options[self::saturation_key] = [];
            foreach ($saturation as $element) {
                if (is_float($element) && Util::isValidSaturation($element)) {
                    array_push($this->options[self::saturation_key], $element);
                } else {
                    // Ignore
                }
            }
            if (empty($this->options[self::saturation_key])) {
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
            $this->options[self::lightness_key] = [$lightness];
        } elseif (is_array($lightness)) {
            $this->options[self::lightness_key] = [];
            foreach ($lightness as $element) {
                if (is_float($element) && Util::isValidLightness($element)) {
                    array_push($this->options[self::lightness_key], $element);
                } else {
                    // Ignore
                }
            }
            if (empty($this->options[self::lightness_key])) {
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
        $range = $this->options[self::hue_key][$hash % count($this->options[self::hue_key])];
        $hueResolution = "727";
        $hue = intval((($hash / count($this->options[self::hue_key])) % $hueResolution)
               * ($range['max'] - $range['min']) / $hueResolution + $range['min']);
        // Saturation
        $hash = intval($hash / 360);
        $saturation = $this->options[self::saturation_key][$hash % count($this->options[self::saturation_key])];
        // Lightness
        $hash = intval($hash / count($this->options[self::lightness_key]));
        $lightness = $this->options[self::lightness_key][$hash % count($this->options[self::lightness_key])];
        
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
