# Modular Laravel Apps

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alejojperez/modular-laravel.svg?style=flat-square)](https://packagist.org/packages/alejojperez/modular-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/alejojperez/modular-laravel/run-tests?label=tests)](https://github.com/alejojperez/modular-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/alejojperez/modular-laravel/Check%20&%20fix%20styling?label=code%20style)](https://github.com/alejojperez/modular-laravel/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/alejojperez/modular-laravel.svg?style=flat-square)](https://packagist.org/packages/alejojperez/modular-laravel)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Docker

If you would like to clone this repo and make changes yourself, you can use docker to test things out:
```bash
docker build --tag="alejojperez/modular-laravel" ./docker
docker run -v `pwd`:/usr/src/app --name=modular-laravel -dit alejojperez/modular-laravel
docker exec -it modular-laravel bash

# Whithin the container
composer install -n
```

## Installation

You can install the package via composer:

```bash
composer require alejojperez/modular-laravel
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="modular-laravel-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="modular-laravel-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="modular-laravel-views"
```

## Usage

```php
$modularLaravel = new ModularLaravel\ModularLaravel();
echo $modularLaravel->echoPhrase('Hello, ModularLaravel!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Alejandro Perez](https://github.com/alejojperez)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
