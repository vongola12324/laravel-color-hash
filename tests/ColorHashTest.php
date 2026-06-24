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

    public function testCustomHueThrowsForInvalidInput()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->hasher->customHue([1, 2, 3]);
    }

    public function testCustomHueThrowsForInvalidHueValue()
    {
        // exercises the validation loop skip (isValidHue fails) → empty options → exception
        $this->expectException(\InvalidArgumentException::class);
        $this->hasher->customHue(['min' => 90, 'max' => -1]);
    }

    public function testCustomSaturationThrowsForInvalidInput()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->hasher->customSaturation('invalid');
    }

    public function testCustomSaturationIgnoresInvalidArrayElements()
    {
        // 2.0 fails isValidSaturation → ignored; 0.5 stays → no exception
        $result = $this->hasher->customSaturation([0.5, 2.0])->rgb('Hello World');
        $this->assertEquals($this->hasher->customSaturation([0.5])->rgb('Hello World'), $result);
    }

    public function testCustomSaturationThrowsForArrayOfAllInvalidValues()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->hasher->customSaturation([2.0]);
    }

    public function testCustomLightnessThrowsForInvalidInput()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->hasher->customLightness('invalid');
    }

    public function testCustomLightnessIgnoresInvalidArrayElements()
    {
        // 2.0 fails isValidLightness → ignored; 0.5 stays → no exception
        $result = $this->hasher->customLightness([0.5, 2.0])->rgb('Hello World');
        $this->assertEquals($this->hasher->customLightness([0.5])->rgb('Hello World'), $result);
    }

    public function testCustomLightnessThrowsForArrayOfAllInvalidValues()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->hasher->customLightness([2.0]);
    }

    public function testCustomHashFallsBackToBkdrForUnknownMethodName()
    {
        // unknown method name → falls back to Hasher::bkdr()
        $this->assertEquals(
            $this->hasher->customHash('nonexistent')->rgb('Hello World'),
            $this->hasher->customHash('bkdr')->rgb('Hello World')
        );
    }

    public function testCustomHashThrowsForNonCallableNonString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->hasher->customHash(123)->rgb('Hello World');
    }
}
