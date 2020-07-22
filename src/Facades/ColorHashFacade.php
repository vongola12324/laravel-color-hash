<?php
namespace Vongola\ColorHash\Facades;

use Illuminate\Support\Facades\Facade;
use \Vongola\ColorHash\Color;

class ColorHashFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Color::class;
    }
}
