<?php
namespace Vongola\ColorHash\Facades;

use Illuminate\Support\Facades\Facade;

class ColorHashFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Vongola\ColorHash\Color::class;
    }
}
