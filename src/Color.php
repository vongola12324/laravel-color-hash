<?php

namespace Vongola\ColorHash;

use Exception;
use InvalidArgumentException;

class Color
{
    private $hasher;
    private $options;


    /**
     * ColorHash constructor.
     */
    public function __construct()
    {
        $this->hasher = 'bkdr';
        $this->options = [
            'hue' => [['min' => 0, 'max' => 360]],
            'saturation' => [0.35, 0.5, 0.65],
            'lightness' => [0.35, 0.5, 0.65],
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
        if (array_key_exists('hue', $options)) {
            $this->customHue($options['hue']);
        }

        // Custom saturation
        if (array_key_exists('saturation', $options)) {
            $this->customSaturation($options['saturation']);
        }

        // Custom lightness
        if (array_key_exists('lightness', $options)) {
            $this->customLightness($options['lightness']);
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
        if (is_numeric($hue)) {
            if (Util::isValidHue($hue)) {
                $this->options['hue'] = [['min' => $hue, 'max' => $hue]];
            } else {
                $failed = true;
            }
        } elseif (is_array($hue)) {
            $this->options['hue'] = [];
            if (array_key_exists('min', $hue) && array_key_exists('max', $hue)) {
                array_push($this->options['hue'], ['min' => $hue['min'], 'max' => $hue['max']]);
            } else {
                foreach ($hue as $element) {
                    if (is_numeric($element)) {
                        if (Util::isValidHue($hue)) {
                            array_push($this->options['hue'], ['min' => $element, 'max' => $element]);
                        } else {
                            // Ignore
                        }
                    } elseif (is_array($element) && array_key_exists('min', $element) && array_key_exists('max', $element)) {
                        if (Util::isValidHue($element['min']) && Util::isValidHue($element['max'])) {
                            array_push($this->options['hue'], ['min' => $element['min'], 'max' => $element['max']]);
                        } else {
                            // Ignore
                        }
                    } else {
                        // Ignore
                    }
                }
            }
            if (empty($this->options['hue'])) {
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
        if (is_float($saturation)) {
            if (Util::isValidSaturation($saturation)) {
                $this->options['saturation'] = [$saturation];
            } else {
                $failed = true;
            }
        } elseif (is_array($saturation)) {
            $this->options['saturation'] = [];
            foreach ($saturation as $element) {
                if (is_float($element)) {
                    if (Util::isValidSaturation($element)) {
                        array_push($this->options['saturation'], $element);
                    } else {
                        $failed = true;
                    }
                } else {
                    // Ignore
                }
            }
            if (empty($this->options['saturation'])) {
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
        if (is_float($lightness)) {
            if (Util::isValidLightness($lightness)) {
                $this->options['lightness'] = [$lightness];
            } else {
                $failed = true;
            }
        } elseif (is_array($lightness)) {
            $this->options['lightness'] = [];
            foreach ($lightness as $element) {
                if (is_float($element)) {
                    if (Util::isValidLightness($element)) {
                        array_push($this->options['lightness'], $element);
                    } else {
                        $failed = true;
                    }
                } else {
                    // Ignore
                }
            }
            if (empty($this->options['lightness'])) {
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
        $range = $this->options['hue'][$hash % count($this->options['hue'])];
        $hueResolution = "727";
        $hue = intval((($hash / count($this->options['hue'])) % $hueResolution)
               * ($range['max'] - $range['min']) / $hueResolution + $range['min']);
        // Saturation
        $hash = intval($hash / 360);
        $saturation = $this->options['saturation'][$hash % count($this->options['saturation'])];
        // Lightness
        $hash = intval($hash / count($this->options['lightness']));
        $lightness = $this->options['lightness'][$hash % count($this->options['lightness'])];
        
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
