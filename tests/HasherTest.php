<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Vongola\ColorHash\Hasher;

class HasherTest extends TestCase
{
    public function testBKDRHash()
    {
        $this->assertEquals(Hasher::bkdr('Test'), Hasher::BKDRHash('Test'));
        $this->assertEquals(Hasher::bkdr('Test'), 24967039386);
    }

    public function testSha256Hash()
    {
        $this->assertEquals(Hasher::sha256('Test'), (int) hexdec(substr(hash('sha256', 'Test'), 0, 8)));
    }

    public function testAPHash()
    {
        $this->assertEquals(Hasher::ap('Test'), Hasher::APHash('Test'));
        $this->assertEquals(Hasher::ap('Test'), 431338847);
    }

    public function testDBJHash()
    {
        $this->assertEquals(Hasher::djb('Test'), Hasher::DJBHash('Test'));
        $this->assertEquals(Hasher::djb('Test'), 1350279892);
    }

    public function testJSHash()
    {
        $this->assertEquals(Hasher::js('Test'), Hasher::JSHash('Test'));
        $this->assertEquals(Hasher::js('Test'), 246668474);
    }
}
