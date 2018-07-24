# Laravel settings wrapper

This package allows you to manage settings in a database.

The settings are handled by groups and types of data (string, boolean, integer, double, array)

## Installation

### Laravel

This package can be used in Laravel 5.4 or higher.

You can install via composer:
```
composer require grooveland/settings
```

In Laravel 5.5 the service provider will automatically get registered. In older versions of the framework just add the service provider in config/app.php file:
```
'providers' => [
    // ...
    \Grooveland\Settings\SettingsServiceProvider::class,
];
```

Now, run your migrations:
```
php artisan migrate
```

## Usage

this is a list of basic methods in model

```
/**
 * Add a settings value
 *
 * @param $name
 * @param $val
 * @param string $type | optional
 * @return bool
 */ 
public static function add($group, $name, $val, $type = Core::DEFAULT_TYPE);

/**
 * Edit a settings by id
 * allow to change name, group, value and type
 *
 * @param $id
 * @param $group
 * @param $name
 * @param $val
 * @param string $type | optional
 * @return bool
 */
public static function edit($id, $group, $name, $val, $type = Core::DEFAULT_TYPE);

/**
 * Get settings by group or by group and name
 * if first is true return only first found setting
 * 
 * @param string $group
 * @param string $name
 * @param boolean $first (false)
 * @return Settings | Array
 */
public static function get(string $group, string $name = null, bool $first = false);

/**
 * Get first settings by group or by group and name
 *
 * @param string $group
 * @param string $name
 * @return Settings | Array
 */
public static function first(string $group, string $name = null);

/**
 * Get first settings name
 *
 * @param string $name
 * @return Settings | Array
 */
public static function one(string $name);

/**
 * Check if exists settings by name
 *
 * @param string $name
 * @return boolean
 */
public static function exists($name);
```

This is a CONSTAT with available types

```
\Grooveland\Settings\Core::TYPES
```

## Contributing
Comming soon.

### Security
If you discover any security-related issues, please email develop@thegrooveland.com instead of using the issue tracker.

## Credits
- [Alejandro de tovar](https://github.com/venespana)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.