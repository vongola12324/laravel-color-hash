# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Install dependencies
composer install

# Run all tests
composer test

# Run a single test method
./vendor/bin/phpunit --filter testHslHash

# Run a single test file
./vendor/bin/phpunit tests/ColorHashTest.php

# Code style check (PHPCS)
./vendor/bin/phpcs src/
```

## Architecture

This is a standalone PHP library (no framework runtime needed) that deterministically maps any string to a color using a hash function. It supports Laravel 5.0–12.x and auto-registers via package discovery.

**Data flow:** `string → hash (int) → HSL → RGB → HEX`

### Core classes

- `src/Color.php` — The main entry point. Holds options (hue ranges, saturation array, lightness array, hasher). Each `customXxx()` method mutates `$this` and returns `$this` for chaining. The `hsl()` method does all the math: it uses the hash modulo index into each option array to pick saturation/lightness, and maps hue within the configured range using a fixed resolution of `727`.
- `src/Hasher.php` — Static hash functions (BKDR, AP, DJB, JS). The default is `bkdr`. Custom hashers can be a string name matching a method on `Hasher`, or any PHP callable.
- `src/Util.php` — Pure static helpers: validation (`isValidHue`, `isValidSaturation`, `isValidLightness`) and color space conversion (`hsl2rgb`, `rgb2hex`).
- `src/Facades/ColorHashFacade.php` — Laravel facade resolving to `Color::class`. Registered as `ColorHash` alias.
- `src/ColorHashServiceProvider.php` — Empty provider; Laravel auto-discovery wires it up via `composer.json`'s `extra.laravel` block.

### Key design detail

`Color` is **stateful and mutable**: calling `customHue()` modifies the instance. In the `ColorHashFacade`, a new `Color` instance is resolved per facade call (because `getFacadeAccessor` returns the class name, not a singleton binding), so customizations do not bleed across facade calls. When using `Color` directly, create a new instance per use or reset options explicitly.

### Hue option format

Hue options are always stored internally as an array of `['min' => ..., 'max' => ...]` ranges. The `customHue()` method normalises scalar integers and single-range arrays into this format before storing.
