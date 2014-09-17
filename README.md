## Laravel Config Writer

Write to Laravel Config files and maintain file integrity.

This library is an extension of the Config component used by Laravel. It adds the ability to write to configuration files.

You can rewrite array values inside a basic configuration file that returns a single array definition (like a Laravel config file) whilst maintaining the file integrity, leaving comments and advanced settings intact.

The following value types are supported for writing: strings, integers and booleans.

### Usage Instructions

Add this to ```app/config/app.php``` under the 'providers' key:

```php
'October\Rain\Config\ConfigServiceProvider',
```

You can now write to config files:

```
Config::write('app.url', 'http://octobercms.com');
```

### Usage outside Laravel

The `Rewrite` class can be used anywhere.

```php
$writeConfig = new October\Rain\Config\Rewrite;
$writeConfig->toFile('path/to/config.php', [
    'item' => 'new value',
    'nested.config.item' => 'value'
]);
```
