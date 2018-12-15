<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Vongola\ColorHash\Color;

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
}
