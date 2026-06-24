# Changelog

## [3.0.0] - 2026-06-25

### Breaking Changes
- Minimum PHP version raised to **8.2**
- Minimum Laravel version raised to **10.0**
- Default hash function changed from BKDR to **SHA-256** (first 8 hex digits), matching the current [zenozeng/color-hash](https://github.com/zenozeng/color-hash) JS implementation
- Default hue no longer uses a `[0, 360]` range — hue now maps to `hash % 359` when no custom hue is set, producing different color outputs than v2
- All hash outputs have changed due to algorithm corrections (see below)

### Algorithm Corrections
- **BKDR hash** updated to match JS: adds `seed2=137`, appends `'x'` to input, uses `Number.MAX_SAFE_INTEGER` overflow guard instead of `& 0x7FFFFFFF`
- **HSL calculation** now uses `fmod()` for hue range math and `ceil()` instead of `intval()` for saturation/lightness index advancement, matching JS behavior
- Fixed a bug where lightness index advancement divided by the wrong array length (lightness count instead of saturation count)
- Fixed PHP 8.5 integer-overflow warnings in all hash functions (`bkdr`, `ap`, `djb`, `js`)
- Fixed PHP 8+ float-modulo deprecation in `hsl()`

### Added
- SHA-256 hasher (`Hasher::sha256()`)
- GitHub Actions CI with PHP 8.2–8.5 matrix
- PHPStan (level 5) static analysis
- PHPCS with PSR-12 standard (`.phpcs.xml`)
- Coveralls code coverage
- Dependabot for automated dependency updates

### Removed
- Travis CI (`.travis.yml`)
- SonarQube (`sonar-project.properties`)
- Support for PHP < 8.2 and Laravel < 10.0

## [2.0.0] - prior

- Laravel 5.0–9.x support
