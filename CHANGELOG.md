# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 2.9.0 - 2019-06-18

### Added

- [zendframework/zend-captcha#42](https://github.com/zendframework/zend-captcha/pull/42) adds support for PHP 7.3.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- [zendframework/zend-captcha#42](https://github.com/zendframework/zend-captcha/pull/42) removes support for laminas-stdlib v2 releases.

### Fixed

- Nothing.

## 2.8.0 - 2018-04-24

### Added

- [zendframework/zend-captcha#39](https://github.com/zendframework/zend-captcha/pull/39) adds support for PHP 7.1 and 7.2.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- [zendframework/zend-captcha#39](https://github.com/zendframework/zend-captcha/pull/39) removes support for HHVM.

### Fixed

- [zendframework/zend-captcha#23](https://github.com/zendframework/zend-captcha/pull/23) fixes an issue with garbage collection of expired CAPTCHA images
  when concurrent requests trigger collection.

- [zendframework/zend-captcha#31](https://github.com/zendframework/zend-captcha/pull/31) fixes using the
  ReCaptcha response as the value parameter to isValid().

## 2.7.0 - 2017-02-20

### Added

- [zendframework/zend-captcha#29](https://github.com/zendframework/zend-captcha/pull/29) adds support for
  laminas-recaptch v3.


### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 2.6.0 - 2016-06-21

### Added

- Adds and publishes documentation to https://docs.laminas.dev/laminas-captcha/
- [zendframework/zend-captcha#20](https://github.com/zendframework/zend-captcha/pull/20) adds support for
  laminas-math v3.

### Deprecated

- Nothing.

### Removed

- [zendframework/zend-captcha#20](https://github.com/zendframework/zend-captcha/pull/20) removes support for
  PHP 5.5

### Fixed

- Nothing.

## 2.5.4 - 2016-02-23

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-captcha#18](https://github.com/zendframework/zend-captcha/pull/18) updates
  dependencies to known-stable, forwards-compatible versions.

## 2.5.3 - 2016-02-22

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-captcha#6](https://github.com/zendframework/zend-captcha/pull/6) ensures that `null`
  values may be passed for options.

## 2.5.2 - 2015-11-23

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- **ZF2015-09**: `Laminas\Captcha\Word` generates a "word" for a CAPTCHA challenge
  by selecting a sequence of random letters from a character set. Prior to this
  vulnerability announcement, the selection was performed using PHP's internal
  `array_rand()` function. This function does not generate sufficient entropy
  due to its usage of `rand()` instead of more cryptographically secure methods
  such as `openssl_pseudo_random_bytes()`. This could potentially lead to
  information disclosure should an attacker be able to brute force the random
  number generation. This release contains a patch that replaces the
  `array_rand()` calls to use `Laminas\Math\Rand::getInteger()`, which provides
  better RNG.

## 2.4.9 - 2015-11-23

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- **ZF2015-09**: `Laminas\Captcha\Word` generates a "word" for a CAPTCHA challenge
  by selecting a sequence of random letters from a character set. Prior to this
  vulnerability announcement, the selection was performed using PHP's internal
  `array_rand()` function. This function does not generate sufficient entropy
  due to its usage of `rand()` instead of more cryptographically secure methods
  such as `openssl_pseudo_random_bytes()`. This could potentially lead to
  information disclosure should an attacker be able to brute force the random
  number generation. This release contains a patch that replaces the
  `array_rand()` calls to use `Laminas\Math\Rand::getInteger()`, which provides
  better RNG.
