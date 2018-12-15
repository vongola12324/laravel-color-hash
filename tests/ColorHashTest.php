<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Vongola\ColorHash\Color;
use Vongola\ColorHash\Facades\ColorHashFacade as ColorHash;

class ColorHashTest extends TestCase
{
    private $hasher;

    public function __construct()
    {
        $this->hasher = new Color();
        parent::__construct();
    }

    public function testHslHash()
    {
        $this->assertEquals($this->hasher->hsl('Hello World'), [225, 0.35, 0.65]);
    }

    public function testRgbHash()
    {
        $this->assertEquals($this->hasher->rgb('Hello World'), [135, 150, 197]);
    }

    public function testHexHash()
    {
        $this->assertEquals($this->hasher->hex('Hello World'), '#8796c5');
    }

    public function testCustomHue()
    {
        $customOption = ['hue' => 90];
        $this->assertEquals($this->hasher->custom($customOption)->rgb('Hello World'), [166, 197, 135]);
        $customOption = ['hue' => ['min' => 90, 'max' => 270]];
        $this->assertEquals($this->hasher->custom($customOption)->rgb('Hello World'), [135, 173, 197]); // Original is 135, 172, 197
        $customOption = ['hue' => [['min' => 30, 'max' => 90], ['min' => 180, 'max' => 210], ['min' => 270, 'max' => 285]]];
        $this->assertEquals($this->hasher->custom($customOption)->rgb('Hello World'), [179, 135, 197]);
    }

    public function testCustomLightness()
    {
//        $customOption = ['lightness' => 0.5];
//        $this->assertEquals($this->hasher->custom($customOption)->rgb('Hello World'), [45, 67, 134]);
        $customOption = ['lightness' => [0.35, 0.5, 0.65]];
        $this->assertEquals($this->hasher->custom($customOption)->rgb('Hello World'), [135, 150, 197]);
    }

    public function testCustomSaturation()
    {
//        $customOption = ['saturation' => 0.5];
//        $this->assertEquals($this->hasher->custom($customOption)->rgb('Hello World'), [45, 67, 134]);
        $customOption = ['saturation' => [0.35, 0.5, 0.65]];
        $this->assertEquals($this->hasher->custom($customOption)->rgb('Hello World'), [135, 150, 197]);
    }

    public function testCustomHashFunction()
    {
        $hashFunc = function ($string) {
            $hash = 0;
            for ($i = 0; $i < strlen($string); $i++) {
                $hash += ord($string[$i]);
            }
            return $hash;
        };
        $customOption = ['hash' => $hashFunc];
        $this->assertEquals($this->hasher->custom($customOption)->rgb('Hello World'), [147, 31, 82]);
    }
}
