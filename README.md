![Package Overview](https://banners.beyondco.de/Filament%20Dynamic%20Settings%20Page%20Plugin.png?theme=light&packageManager=composer+require&packageName=ibrahim-bedir%2Ffilament-dynamic-settings-page&pattern=architect&style=style_1&description=Save+your+settings+quickly+and+simply.&md=1&showWatermark=0&fontSize=75px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

  
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
    'navigation' => [
        'group' => 'Settings',
        'sort' => '1',
        'icon' => 'heroicon-o-cog'
    ]
];
```
## Lisans

The MIT License [MIT](https://choosealicense.com/licenses/mit/). Please see License File for more information.

  
