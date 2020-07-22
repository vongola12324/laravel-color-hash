# laravel-color-hash
![](https://travis-ci.org/vongola12324/laravel-color-hash.svg?branch=master) ![](https://img.shields.io/codecov/c/github/vongola12324/laravel-color-hash.svg) ![](https://img.shields.io/packagist/php-v/vongola12324/laravel-color-hash.svg) ![](https://img.shields.io/packagist/v/vongola12324/laravel-color-hash.svg)  
ColorHash Library for Laravel 5.0+. Port from [zenozeng/color-hash](https://github.com/zenozeng/color-hash).

## Installation
```bash
composer require vongola12324/laravel-color-hash
```
## Usage
### Basic
```php
// in HSL, Hue ∈ [0, 360), Saturation ∈ [0, 1], Lightness ∈ [0, 1]
ColorHash::hsl('Hello World'); // [185, 0.35, 0.35]

// in RGB, R, G, B ∈ [0, 255]
ColorHash::rgb('Hello World'); // [58, 115, 120]

// in HEX
ColorHash::hex('Hello World'); // '#3a7378'
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
ColorHash::customHash($hashFunc)->rgb('Hello World'); // [31, 147, 109]

// Custom Hue
ColorHash::customHue(90)->rgb('Hello World'); // [89, 120, 58]
ColorHash::customHue(['min' => 90, 'max' => 270])->rgb('Hello World'); // [58, 118, 120]
ColorHash::customHue([['min' => 30, 'max' => 90], ['min' => 180, 'max' => 210], ['min' => 270, 'max' => 285]])->rgb('Hello World'); // [120, 100, 58]

// Custom Saturation
ColorHash::customSaturation(0.5)->rgb('Hello World'); // [45, 126, 134]
ColorHash::customSaturation([0.35, 0.5, 0.65])->rgb('Hello World'); // [58, 115, 120]

// Custom Lightness
ColorHash::customLightness(0.5)->rgb('Hello World'); // [83, 165, 172]
ColorHash::customLightness([0.35, 0.5, 0.65])->rgb('Hello World'); // [58, 115, 120]
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
