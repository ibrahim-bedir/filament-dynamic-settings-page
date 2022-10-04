![Package Overview](https://banners.beyondco.de/Filament%20Dynamic%20Settings%20Page%20Plugin.png?theme=light&packageManager=composer+require&packageName=ibrahim-bedir%2Ffilament-dynamic-settings-page&pattern=architect&style=style_1&description=Save+your+settings+quickly+and+simply.&md=1&showWatermark=0&fontSize=75px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)


<img width="70%" src="https://raw.githubusercontent.com/ibrahim-bedir/filament-dynamic-settings-page/main/screenshoot/screenshoot.gif"/>
  
# Filament Dynamic Settings Page Plugin

Save your settings quickly and simply.



## Installation

You can install the package via composer:

```bash
composer require ibrahim-bedir/filament-dynamic-settings-page
```

  ## Usage

Just install the package and you're ready to go!
## Configuration

You can publish the config file and migrations with:

```bash
php artisan filament-dynamic-settings-page:install
```

  This is the contents of the published config file:

```php
<?php

return [
    'title' => 'Settings',
    'navigation' => [
        'label' => 'Settings',
        'group' => 'Settings',
        'sort' => '1',
        'icon' => 'heroicon-o-cog'
    ],
    'breadcrumbs' => [
        'Settings',
    ],
    'tool' => [
        "enable" => false
    ],
    'permission' => [
        'enable' => false,
        //  permission name 
        // 'name' => 'list.settings'
        'name' => ''
    ]
];
```

easily accessible from the front:

add app/helpers.php

```php
<?php

use IbrahimBedir\FilamentDynamicSettingsPage\Models\Setting;

function setting($key)
{
    return Setting::where('key', $key)->first()->value('value');
}
```

composer.json
```php
...

"autoload": {

    "psr-4": {

        "App\\": "app/",

        "Database\\Factories\\": "database/factories/",

        "Database\\Seeders\\": "database/seeders/"

    },

    "files": [

        "app/helpers.php"

    ]

},

...
```

```php
    composer dump-autoload
```
welcome.blade.php

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
    </head>
    <body class="antialiased">

        {{ setting('site.title') }}

    </body>
</html>
```

## Things to do list

- more fields
- fields options

## Lisans

The MIT License [MIT](https://choosealicense.com/licenses/mit/). Please see License File for more information.

  
