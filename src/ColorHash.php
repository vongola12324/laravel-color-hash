<?php

namespace Vongola\ColorHash;

use Shahonseven\ColorHash as Hasher;

class ColorHash
{
    private $hasher;

    /**
     * ColorHash constructor.
     */
    public function __construct()
    {
	$this->hasher = new Hasher();
    }

    /**
     * Convert a string to a hsl array
     */
    public function hsl($string)
    {
	return $this->hasher->hsl($string);
    }

    /**
     * Convert a string to a rgb array
     */
    public function rgb($string)
    {
	return $this->hasher->rgb($string);
    }

    /**
     * Convert a string to color hex
     */
    public function hex($string)
    {
	return $this->hasher->hex($string);
    }
}
