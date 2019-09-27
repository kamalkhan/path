# Path

[![Travis Build Status][icon-status]][link-status]
[![Packagist Downloads][icon-downloads]][link-downloads]
[![License][icon-license]](LICENSE.md)

Utilities for working with paths in PHP.

- [Install](#install)
- [Usage](#usage)
  - [Sanitize](#sanitize)
  - [Join](#join)
  - [Absolute](#absolute)
  - [Normalize](#normalize)
  - [Is Absolute](#is-absolute)
  - [Is Root](#is-root)
- [Static Access](#static-access)
- [Changelog](#changelog)
- [Testing](#testing)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Install

You may install this package using [composer][link-composer].

```shell
$ composer require bhittani/path --prefer-dist
```

## Usage

This packages offers some helpful utilities when working with paths.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$path = new \Bhittani\Path\Path();

// Use the api calls as demonstrated below.
```

### Sanitize

Convert back slashes to forward slashes.

```php
echo $path->sanitize('\foo/bar\baz'); // '/foo/bar/baz'
```

### Join

Join parts of a paths.

```php
echo $path->join('foo', 'bar', '/baz/'); // 'foo/bar/baz'
```

> This will also sanitize the paths under the hood.

### Absolute

Convert a path to an absolute path.

```php
echo $path->absolute('/foo'); // '/foo'
echo $path->absolute('foo'); // getcwd().'/foo'
echo $path->absolute('/foo', true); // getcwd().'/foo'
```

> The second (`boolean`) argument will forcely append the path to `getcwd()`.

### Normalize

Normalize is identical to `join` but it ensures an absolute path.

```php
echo $path->normalize('/foo', 'bar/', '/baz/'); // '/foo/bar/baz'
echo $path->normalize('foo', 'bar', '/baz/'); // getcwd().'/foo'
```

### Is Absolute

Determines whether a path is absolute or not.

```php
echo $path->isAbsolute('/foo/bar'); // true
echo $path->isAbsolute('foo/bar'); // false
```

### Is Root

Determines whether a path is a root or not.

```php
echo $path->isRoot('/'); // true
echo $path->isRoot('c:/'); // true
echo $path->isRoot('http://'); // true
echo $path->isRoot('http://example.com'); // true
echo $path->isRoot('/foo'); // false
```

> Any root path is also an absolute path.
> So `isAbsolute` will always be true when `isRoot` is true.

## Static Access

A `StaticPath` class is available.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Bhittani\Path\StaticPath;

echo StaticPath::normalize('/foo/', '/bar/'); // '/foo/bar'
```

> Any of the public methods may be invoked by static access.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed.

## Testing

```shell
git clone https://github.com/kamalkhan/path

cd path

composer install

composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email `shout@bhittani.com` instead of using the issue tracker.

## Credits

- [Kamal Khan](http://bhittani.com)
- [All Contributors](https://github.com/kamalkhan/path/contributors)

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

<!--Status-->
[icon-status]: https://img.shields.io/travis/kamalkhan/path.svg?style=flat-square
[link-status]: https://travis-ci.org/kamalkhan/path
<!--Downloads-->
[icon-downloads]: https://img.shields.io/packagist/dt/bhittani/path.svg?style=flat-square
[link-downloads]: https://packagist.org/packages/bhittani/path
<!--License-->
[icon-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
<!--composer-->
[link-composer]: https://getcomposer.org
