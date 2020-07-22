<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Vongola\ColorHash\Hasher;

class HasherTest extends TestCase
{
    public function testBKDRHash()
    {
        $this->assertEquals(Hasher::bkdr('Test'), Hasher::BKDRHash('Test'));
        $this->assertEquals(Hasher::bkdr('Test'), 190588086);
    }

    public function testAPHash()
    {
        $this->assertEquals(Hasher::ap('Test'), Hasher::APHash('Test'));
        $this->assertEquals(Hasher::ap('Test'), 372618591);
    }

    public function testDBJHash()
    {
        $this->assertEquals(Hasher::djb('Test'), Hasher::DJBHash('Test'));
        $this->assertEquals(Hasher::djb('Test'), 1350279892);
    }

    public function testJSHash()
    {
        $this->assertEquals(Hasher::js('Test'), Hasher::JSHash('Test'));
        $this->assertEquals(Hasher::js('Test'), 1689509050);
    }
}
