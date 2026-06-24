<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Vongola\ColorHash\Util;

class UtilTest extends TestCase
{
    public function testIsBetweenReturnsTrueForValueInRange()
    {
        $this->assertTrue(Util::isBetween(5, 0, 10));
    }

    public function testIsBetweenReturnsFalseForValueOutOfRange()
    {
        $this->assertFalse(Util::isBetween(15, 0, 10));
    }

    public function testIsValidHueReturnsFalseForOutOfRange()
    {
        $this->assertFalse(Util::isValidHue(361));
        $this->assertFalse(Util::isValidHue(-1));
    }

    public function testIsValidHueReturnsFalseForNonNumeric()
    {
        $this->assertFalse(Util::isValidHue('abc'));
    }

    public function testIsValidSaturationReturnsFalseForOutOfRange()
    {
        $this->assertFalse(Util::isValidSaturation(1.5));
        $this->assertFalse(Util::isValidSaturation(-0.1));
    }

    public function testIsValidLightnessReturnsFalseForOutOfRange()
    {
        $this->assertFalse(Util::isValidLightness(1.1));
        $this->assertFalse(Util::isValidLightness(-0.1));
    }

    public function testHsl2RgbWithZeroSaturation()
    {
        // integer 0 triggers the saturation === 0 greyscale branch
        $result = Util::hsl2rgb([0, 0, 0.5]);
        $this->assertEquals([0.5, 0.5, 0.5], $result);
    }

    public function testHsl2RgbWithLowLightness()
    {
        // lightness=0.35 < 0.5 → q = lightness*(1+saturation)
        // hue=0: tC values [0.333, 0, -0.333] — covers tC<0 normalization and calcFinalColor tC<1/6
        $result = Util::hsl2rgb([0, 0.5, 0.35]);
        $this->assertEquals([134, 45, 45], $result);
    }

    public function testHsl2RgbWithHighHue()
    {
        // hue=270: tC values [1.083→0.083, 0.75, 0.417] — covers tC>1 normalization and calcFinalColor tC<1/6
        $result = Util::hsl2rgb([270, 0.5, 0.65]);
        $this->assertEquals([166, 121, 210], $result);
    }

    public function testRgb2Hex()
    {
        $this->assertEquals('#7984d2', Util::rgb2hex([121, 132, 210]));
    }
}
