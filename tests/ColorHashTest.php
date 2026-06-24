<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Vongola\ColorHash\Color;

class ColorHashTest extends TestCase
{
    private $hasher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hasher = new Color();
    }

    public function testHslHash()
    {
        $this->assertEquals($this->hasher->hsl('Hello World'), [233, 0.5, 0.65]);
    }

    public function testRgbHash()
    {
        $this->assertEquals($this->hasher->rgb('Hello World'), [121, 132, 210]);
    }

    public function testHexHash()
    {
        $this->assertEquals($this->hasher->hex('Hello World'), '#7984d2');
    }

    public function testCustomHue()
    {
        // Use custom function
        $this->assertEquals(
            $this->hasher->custom(['hue' => 90])->rgb('Hello World'),
            $this->hasher->customHue(90)->rgb('Hello World')
        );
        // Use customHue function
        $customOption = 90;
        $this->assertEquals($this->hasher->customHue($customOption)->rgb('Hello World'), [166, 210, 121]);
        $customOption = ['min' => 90, 'max' => 270];
        $this->assertEquals($this->hasher->customHue($customOption)->rgb('Hello World'), [121, 163, 210]);
        $customOption = [['min' => 30, 'max' => 90], ['min' => 180, 'max' => 210], ['min' => 270, 'max' => 285]];
        $this->assertEquals($this->hasher->customHue($customOption)->rgb('Hello World'), [121, 185, 210]);
    }

    public function testCustomLightness()
    {
        // Use custom function
        $this->assertEquals(
            $this->hasher->custom(['lightness' => 0.5])->rgb('Hello World'),
            $this->hasher->customLightness(0.5)->rgb('Hello World')
        );
        // Use customLightness function
        $customOption = 0.5;
        $this->assertEquals($this->hasher->customLightness($customOption)->rgb('Hello World'), [64, 79, 191]);
        $customOption = [0.35, 0.5, 0.65];
        $this->assertEquals($this->hasher->customLightness($customOption)->rgb('Hello World'), [121, 132, 210]);
    }

    public function testCustomSaturation()
    {
        // Use custom function
        $this->assertEquals(
            $this->hasher->custom(['saturation' => 0.5])->rgb('Hello World'),
            $this->hasher->customSaturation(0.5)->rgb('Hello World')
        );
        // Use customSaturation function
        $customOption = 0.5;
        $this->assertEquals($this->hasher->customSaturation($customOption)->rgb('Hello World'), [64, 79, 191]);
        $customOption = [0.35, 0.5, 0.65];
        $this->assertEquals($this->hasher->customSaturation($customOption)->rgb('Hello World'), [121, 132, 210]);
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
        // Use custom function
        $this->assertEquals(
            $this->hasher->custom(['hash' => $hashFunc])->rgb('Hello World'),
            $this->hasher->customHash($hashFunc)->rgb('Hello World')
        );
        // Use customHash function
        $this->assertEquals($this->hasher->customHash($hashFunc)->rgb('Hello World'), [172, 83, 122]);
    }
}
