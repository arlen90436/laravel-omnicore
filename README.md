# Omnicore JSON-RPC Service Provider for Laravel

## About
This package allows you to make JSON-RPC calls to Bitcoin Core JSON-RPC server from your laravel project.
It's based on [arlen/omni-tools](https://github.com/arlen90436/omni-tools) project - fully unit-tested Bitcoin JSON-RPC client powered by GuzzleHttp.

## Quick Installation
1. Install package:
```sh
composer require arlen/laravel-omnicore "^1.0"
```

2. _(skip if using Laravel 5.5 or newer)_ Add service provider and facade to `./config/app.php`
```php
...
'providers' => [
    ...
    Arlen\Omnicore\Providers\ServiceProvider::class,
];
...
'aliases' => [
    ...
    'Omnitools' => Arlen\Omnicore\Facades\Omnicored::class,
];
```
3. Publish config file
```sh
php artisan vendor:publish --provider="Arlen\Omnicore\Providers\ServiceProvider"
```


## Usage
This package provides simple and intuitive API to make RPC calls to Omnicore USDT Core (and some altcoins)
```php
$hash = '000000000001caba23d5a17d5941f0c451c4ac221cbaa6c60f27502f53f87f68';
$block = omnicored()->getBlock($hash);
dump($block->get());
```

## Documentation

## Requirements
* PHP 7.1 or higher
* Laravel 5.2 or higher


## License
This product is distributed under the [MIT license](https://github.com/arlen90436/laravel-omnicore/blob/master/LICENSE).

## Donations

If you like this project, please consider donating:<br>
**BTC/USDT**: 3M9DL9bsr8oumyRATeVRTPk62u2S3yrH4Z<br>

❤Thanks for your support!❤
