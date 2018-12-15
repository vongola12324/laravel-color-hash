<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Vongola\ColorHash\ColorHash;

class ColorHashTest extends TestCase
{
    private $hasher;

    public function __construct()
    {
        $this->hasher = new ColorHash();
        parent::__construct();
    }

    public function testHslHash()
    {
        // This is the Number.MAX_SAFE_INT version (JS) result.
        // $this->assertEquals($this->hasher->hsl('Hello World'), [255, 0.65, 0.35]);
    
        // This is the PHP_INT_MAX version result.
        $this->assertEquals($this->hasher->hsl('Hello World'), [158, 0.65, 0.5]);
    }

    public function testRgbHash()
    {
        // This is the Number.MAX_SAFE_INT version (JS) result.
        // $this->assertEquals($this->hasher->rgb('Hello World'), [135, 150, 197]);
    
        // This is the PHP_INT_MAX version result.
        $this->assertEquals($this->hasher->rgb('Hello World'), [45, 210, 150]);
    }

    public function testHexHash()
    {
        // This is the Number.MAX_SAFE_INT version (JS) result.
        // $this->assertEquals($this->hasher->hex('Hello World'), '#8796c5');
    
        // This is the PHP_INT_MAX version result.
        $this->assertEquals($this->hasher->hex('Hello World'), '#2dd296');
    }
}
