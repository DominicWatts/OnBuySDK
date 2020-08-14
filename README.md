# OnBuy Php SDK - Work In Progress (WIP)

OnBuy - UK Online Marketplace & Amazon Alternative

Unofficial PHP SDK to interact with OnBuy API

![phpcs](https://github.com/DominicWatts/OnBuySDK/workflows/phpcs/badge.svg)

![PHPCompatibility](https://github.com/DominicWatts/OnBuySDK/workflows/PHPCompatibility/badge.svg)

![PHPStan](https://github.com/DominicWatts/OnBuySDK/workflows/PHPStan/badge.svg)

## Install Instructions

`composer require dominicwatts/onbuysdk`

There is a composer archive at:

    https://packagist.org/packages/dominicwatts/onbuysdk

## API Documentation

    https://docs.api.onbuy.com/?version=latest

## Connection Details

The API settings page can be found in your Seller Control Panel. There are two sets of keys, Live and Test. You should use the Test keys when integrating with the API and switch to the Live Keys when you are ready to go live.

    https://seller.onbuy.com/inventory/integrations/onbuy-api/

## Usage

To connect to any API method, you first need to obtain your temporary secret access token.

```php
require 'vendor/autoload.php';

$config = [
    'consumer_key' => 'test_consumer_key',
    'secret_key' => 'test_secret_key'
];

$auth = new Auth(
    $config
);
$auth->getToken();
````

    The access token is only valid for 15 minutes, after which a new token will need to be requested.

## Notes

More notes and Docs to follow