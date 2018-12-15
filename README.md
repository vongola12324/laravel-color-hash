# laravel-color-hash
![](https://travis-ci.org/vongola12324/laravel-color-hash.svg?branch=master)  
PHP Port of ColorHash Javascript Library for Laravel.

## Installation
```bash
composer require vongola12324/laravel-color-hash
```
## Usage
### Basic
```php
// in HSL, Hue ∈ [0, 360), Saturation ∈ [0, 1], Lightness ∈ [0, 1]
ColorHash::hsl('Hello World'); // [225, 0.35, 0.65]

// in RGB, R, G, B ∈ [0, 255]
ColorHash::rgb('Hello World'); // [135, 150, 197]

// in HEX
ColorHash::hex('Hello World'); // '#8796c5'
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
ColorHash::custom(['hash' => $hashFunc])->rgb('Hello World'); // [147, 31, 82]

// Custom Hue
ColorHash::custom(['hue' => 90])->rgb('Hello World'); // [166, 197, 135]
ColorHash::custom(['hue' => ['min' => 90, 'max' => 270]])->rgb('Hello World'); // [135, 173, 197]
ColorHash::custom(['hue' => [['min' => 30, 'max' => 90], ['min' => 180, 'max' => 210], ['min' => 270, 'max' => 285]]])->rgb('Hello World'); // [179, 135, 197]

// Custom Lightness
ColorHash::custom(['lightness' => 0.5])->rgb('Hello World'); // Broken, don't use it
ColorHash::custom(['lightness' => [0.35, 0.5, 0.65]])->rgb('Hello World'); // [135, 150, 197]

// Custom Saturation
ColorHash::custom(['saturation' => 0.5])->rgb('Hello World'); // Broken, don't use it
ColorHash::custom(['saturation' => [0.35, 0.5, 0.65]])->rgb('Hello World'); // [135, 150, 197]
```

>  The results of CustomLightness and CustomSaturation is wrong when there is only one parameter of them.  
> I'm not sure why did that happened.  This might be fixed in later release.

## License
MIT. 
