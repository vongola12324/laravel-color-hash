# laravel-color-hash
PHP Port of ColorHash Javascript Library for Laravel

## Installation
```bash
composer require vongola12324/laravel-color-hash
```
## Usage
```php
// in HSL, Hue ∈ [0, 360), Saturation ∈ [0, 1], Lightness ∈ [0, 1]
ColorHash::hsl('Hello World'); // [158, 0.65, 0.5]

// in RGB, R, G, B ∈ [0, 255]
ColorHash::rgb('Hello World'); // [45, 210, 150]

// in HEX
ColorHash::hex('Hello World'); // '#2dd296'
```  
**Warning: The results is different from the results of original package, see the [issue#2](https://github.com/shahonseven/php-color-hash/issues/2) of original package.**

## Notice
This is a wrapped version of [shahonseven/php-color-hash](https://github.com/shahonseven/php-color-hash) for laravel 5.0+.  
If you have any question, please use the issue tracker. 

## License
MIT. 
