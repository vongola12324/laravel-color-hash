<?php

namespace Vongola\ColorHash;

use Exception;
use InvalidArgumentException;

class Color
{
    private $hasher;
    private $options;
    const hueKey = 'hue';
    const saturationKey = 'saturation';
    const lightnessKey = 'lightness';

    /**
     * ColorHash constructor.
     */
    public function __construct()
    {
        $this->hasher = 'bkdr';
        $this->options = [
            static::hueKey => [['min' => 0, 'max' => 360]],
            self::saturationKey => [0.35, 0.5, 0.65],
            self::lightnessKey => [0.35, 0.5, 0.65],
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
        if (array_key_exists(self::hueKey, $options)) {
            $this->customHue($options[self::hueKey]);
        }

        // Custom saturation
        if (array_key_exists(self::saturationKey, $options)) {
            $this->customSaturation($options[self::saturationKey]);
        }

        // Custom lightness
        if (array_key_exists(self::lightnessKey, $options)) {
            $this->customLightness($options[self::lightnessKey]);
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
        if (is_numeric($hue) && Util::isValidHue($hue)) {
            $this->options[self::hueKey] = [['min' => $hue, 'max' => $hue]];
        } elseif (is_array($hue)) {
            $this->options[self::hueKey] = [];
            if (array_key_exists('min', $hue) && array_key_exists('max', $hue)) {
                array_push($this->options[self::hueKey], ['min' => $hue['min'], 'max' => $hue['max']]);
            } else {
                foreach ($hue as $element) {
                    if (is_numeric($element) && Util::isValidHue($hue)) {
                        array_push($this->options[self::hueKey], ['min' => $element, 'max' => $element]);
                    } elseif (is_array($element) && array_key_exists('min', $element) && array_key_exists('max', $element)
                        && Util::isValidHue($element['min']) && Util::isValidHue($element['max'])) {
                        array_push($this->options[self::hueKey], ['min' => $element['min'], 'max' => $element['max']]);    
                    } else {
                        // Ignore
                    }
                }
            }
            if (empty($this->options[self::hueKey])) {
                $failed = true;
            }
        } else {
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
            $this->options[self::saturationKey] = [$saturation];
        } elseif (is_array($saturation)) {
            $this->options[self::saturationKey] = [];
            foreach ($saturation as $element) {
                if (is_float($element) && Util::isValidSaturation($element)) {
                    array_push($this->options[self::saturationKey], $element);
                } else {
                    // Ignore
                }
            }
            if (empty($this->options[self::saturationKey])) {
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
            $this->options[self::lightnessKey] = [$lightness];
        } elseif (is_array($lightness)) {
            $this->options[self::lightnessKey] = [];
            foreach ($lightness as $element) {
                if (is_float($element) && Util::isValidLightness($element)) {
                    array_push($this->options[self::lightnessKey], $element);
                } else {
                    // Ignore
                }
            }
            if (empty($this->options[self::lightnessKey])) {
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
            throw new Exception('Can not execute hash function.');
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
        $range = $this->options[self::hueKey][$hash % count($this->options[self::hueKey])];
        $hueResolution = "727";
        $hue = intval((($hash / count($this->options[self::hueKey])) % $hueResolution)
               * ($range['max'] - $range['min']) / $hueResolution + $range['min']);
        // Saturation
        $hash = intval($hash / 360);
        $saturation = $this->options[self::saturationKey][$hash % count($this->options[self::saturationKey])];
        // Lightness
        $hash = intval($hash / count($this->options[self::lightnessKey]));
        $lightness = $this->options[self::lightnessKey][$hash % count($this->options[self::lightnessKey])];
        
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
