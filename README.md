# Laravel Config Writer

Write to Laravel Config files and maintain file integrity.

This library is an extension of the Config component used by Laravel. It adds the ability to write to configuration files.

You can rewrite array values inside a basic configuration file that returns a single array definition (like a Laravel config file) whilst maintaining the file integrity, leaving comments and advanced settings intact.

The following value types are supported for writing: strings, integers, booleans and single-dimension arrays.

## Support

This provider is designed to be used in Laravel from `5.4` version.

## Setup

Install through composer:
```
composer require "daftspunk/laravel-config-writer"
```

Add this to `app/config/app.php` under the 'providers' key:

```php
October\Rain\Config\ServiceProvider::class,
```

### Lumen case

Add this to `bootstrap/app.php` in the 'service providers' section declaration:

```php
$app->register(October\Rain\Config\ServiceProvider::class);
```

## Usage

You can write to config files like this:

```php
Config::write('app.url', 'http://domain.com');

app('config')->write('app.url', 'http://domain.com');
```


### Outside Laravel

The `Rewrite` class can be used anywhere.

```php
$writeConfig = new October\Rain\Config\DataWriter\Rewrite;
$writeConfig->toFile('path/to/config.php', [
    'item' => 'new value',
    'nested.config.item' => 'value',
    'arrayItem' => ['Single', 'Level', 'Array', 'Values'],
    'numberItem' => 3,
    'booleanItem' => true
]);
```
