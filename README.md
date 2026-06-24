# laravel-color-hash

![CI](https://github.com/vongola12324/laravel-color-hash/actions/workflows/ci.yml/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/vongola12324/laravel-color-hash/badge.svg?branch=master)](https://coveralls.io/github/vongola12324/laravel-color-hash?branch=master)
![PHP](https://img.shields.io/packagist/php-v/vongola12324/laravel-color-hash.svg)
![Version](https://img.shields.io/packagist/v/vongola12324/laravel-color-hash.svg)

ColorHash Library for Laravel 10.0+. Port from [zenozeng/color-hash](https://github.com/zenozeng/color-hash).

## Requirements

- PHP 8.2+
- Laravel 10.0+

## Installation
```bash
composer require vongola12324/laravel-color-hash
```
## Usage
### Basic
```php
// in HSL, Hue ∈ [0, 360), Saturation ∈ [0, 1], Lightness ∈ [0, 1]
ColorHash::hsl('Hello World'); // [233, 0.5, 0.65]

// in RGB, R, G, B ∈ [0, 255]
ColorHash::rgb('Hello World'); // [121, 132, 210]

// in HEX
ColorHash::hex('Hello World'); // '#7984d2'
```  
### Custom
```php
// Custom Hash Function
$hashFunc = function ($string) {
    $hash = 0;
    for ($i = 0; $i < strlen($string); $i++) {
        $hash += ord($string[$i]);
    }
    return $hash;
}
ColorHash::customHash($hashFunc)->rgb('Hello World'); // [172, 83, 122]

// Custom Hue
ColorHash::customHue(90)->rgb('Hello World'); // [166, 210, 121]
ColorHash::customHue(['min' => 90, 'max' => 270])->rgb('Hello World'); // [121, 163, 210]
ColorHash::customHue([['min' => 30, 'max' => 90], ['min' => 180, 'max' => 210], ['min' => 270, 'max' => 285]])->rgb('Hello World'); // [121, 185, 210]

// Custom Saturation
ColorHash::customSaturation(0.5)->rgb('Hello World'); // [64, 79, 191]
ColorHash::customSaturation([0.35, 0.5, 0.65])->rgb('Hello World'); // [121, 132, 210]

// Custom Lightness
ColorHash::customLightness(0.5)->rgb('Hello World'); // [64, 79, 191]
ColorHash::customLightness([0.35, 0.5, 0.65])->rgb('Hello World'); // [121, 132, 210]
```

All customXXX method can be used in a single custom method by passing an option array, for example:
```php
ColorHash::customHue(90)->rgb('Test');
// Is Equal to
ColorHash::custom(['hue' => 90])->rgb('Test');
```

Also can combine with more than one custom option, for example:
```php
ColorHash::customHue(90)->customSaturation(0.5)->customLightness(0.5)->customHash($hashFunc)->rgb('Test');
// Is Equal to
ColorHash::custom(['hue' => 90, 'saturation' => 0.5, 'lightness' => 0.5, 'hash' => $hashFunc])->rgb('Test');
```

## License
MIT. 
