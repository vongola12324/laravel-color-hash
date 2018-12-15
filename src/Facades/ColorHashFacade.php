<?php
namespace Vongola\ColorHash\Facades;

use Illuminate\Support\Facades\Facade;

class ImgurFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Vongola\ColorHash\ColorHash::class;
    }
}
