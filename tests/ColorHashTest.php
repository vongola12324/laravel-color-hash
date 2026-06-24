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
        $this->assertEquals($this->hasher->hsl('Hello World'), [30, 0.65, 0.65]);
    }

    public function testRgbHash()
    {
        $this->assertEquals($this->hasher->rgb('Hello World'), [224, 166, 108]);
    }

    public function testHexHash()
    {
        $this->assertEquals($this->hasher->hex('Hello World'), '#e0a66c');
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
        $this->assertEquals($this->hasher->customHue($customOption)->rgb('Hello World'), [166, 224, 108]);
        $customOption = ['min' => 90, 'max' => 270];
        $this->assertEquals($this->hasher->customHue($customOption)->rgb('Hello World'), [137, 224, 108]);
        $customOption = [['min' => 30, 'max' => 90], ['min' => 180, 'max' => 210], ['min' => 270, 'max' => 285]];
        $this->assertEquals($this->hasher->customHue($customOption)->rgb('Hello World'), [166, 108, 224]);
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
        $this->assertEquals($this->hasher->customLightness($customOption)->rgb('Hello World'), [210, 128, 45]);
        $customOption = [0.35, 0.5, 0.65];
        $this->assertEquals($this->hasher->customLightness($customOption)->rgb('Hello World'), [224, 166, 108]);
    }

    public function testCustomSaturation()
    {
        // Use custom function
        $this->assertEquals(
            $this->hasher->custom(['saturation' => 0.5])->rgb('Hello World'),
            $this->hasher->customSaturation(0.5)->rgb('Hello World')
        );
        // Use customLightness function
        $customOption = 0.5;
        $this->assertEquals($this->hasher->customSaturation($customOption)->rgb('Hello World'), [210, 166, 121]);
        $customOption = [0.35, 0.5, 0.65];
        $this->assertEquals($this->hasher->customSaturation($customOption)->rgb('Hello World'), [224, 166, 108]);
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
        $this->assertEquals($this->hasher->customHash($hashFunc)->rgb('Hello World'), [31, 147, 109]);
    }
}
