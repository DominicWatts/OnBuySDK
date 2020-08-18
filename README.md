# OnBuy Php SDK

OnBuy - UK Online Marketplace & Amazon Alternative

Unofficial PHP SDK to interact with OnBuy API

![phpcs](https://github.com/DominicWatts/OnBuySDK/workflows/phpcs/badge.svg)

![PHPCompatibility](https://github.com/DominicWatts/OnBuySDK/workflows/PHPCompatibility/badge.svg)

![PHPStan](https://github.com/DominicWatts/OnBuySDK/workflows/PHPStan/badge.svg)

![PHPUnit](https://github.com/DominicWatts/OnBuySDK/workflows/PHPUnit/badge.svg)

[![Coverage Status](https://coveralls.io/repos/github/DominicWatts/OnBuySDK/badge.svg)](https://coveralls.io/github/DominicWatts/OnBuySDK)

## Install Instructions

`composer require dominicwatts/onbuysdk`

There is a composer archive at:

[https://packagist.org/packages/dominicwatts/onbuysdk](https://packagist.org/packages/dominicwatts/onbuysdk)

[Coveralls Status](https://coveralls.io/github/DominicWatts/OnBuySDK)

## API Documentation

[https://docs.api.onbuy.com/?version=latest](https://docs.api.onbuy.com/?version=latest)

## Connection Details

The API settings page can be found in your Seller Control Panel. There are two sets of keys, Live and Test. You should use the Test keys when integrating with the API and switch to the Live Keys when you are ready to go live.

[https://seller.onbuy.com/inventory/integrations/onbuy-api/](https://seller.onbuy.com/inventory/integrations/onbuy-api/)

## Usage

### Get token

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

$auth->getToken(); // This is your token
```

    The access token is only valid for 15 minutes, after which a new token will need to be requested.

To debug

```
$auth->getResponse()->getBody();
$auth->getResponseArray();
$auth->getExpires(); 
```

### General process

  1.  Load token
  2.  Build request
  3.  Perform request and return results

### Brands

```php
$brand = new Brand($auth->getToken());

$brand->getBrand('keyword', 'asc', 10, 0);
$brand->getResponse();

$brand->getBrandById(123);
$brand->getResponse();
```

### Categories

#### Categories

```php
$category = new Category($auth->getToken());

$category->getCategory([
    'name' => 'test'
]);
$category->getResponse(); 

$category->getCategory([
    'name' => 'test',
    'category_type_id' => 1 // department
]);
$category->getResponse(); 

$category->getCategoryById(13490);
$category->getResponse(); 
```

#### Categories Features

```php
$categoryFeature = new Feature($auth->getToken());

$categoryFeature->getFeatureById(13490);
$categoryFeature->getResponse();
```

#### Categories Technical Details

```php
$categoryTechnical = new Technical($auth->getToken());

$categoryTechnical->getTechnicalDetailById(13490);
$categoryTechnical->getResponse();

$categoryTechnical->getGroupById(13490, 125);
$categoryTechnical->getResponse();
```

#### Categories Variants

```php
$categoryVariant = new Variant($auth->getToken());

$categoryVariant->getVariantId(13490);
$categoryVariant->getResponse();
```

### Commission

```php
$commission = new Commission($auth->getToken());

$commission->getTier();
$commission->getResponse();

$commission->getTierById(13490, 125);
$commission->getResponse();
```

### Condition

```php
$condition = new Condition($auth->getToken());

$condition->getCondition();
$condition->getResponse();
```

### Order

```php
$order = new Order($auth->getToken());

$order->getOrder(
    [
        'status' => 'awaiting_dispatch',
        'previously_exported' => 0
    ],
    [
        'created' => 'asc'
    ]
);
$order->getResponse();

$order->getOrderById('T9R7V');
$order->getResponse();

$order->dispatchOrder(['order_id' => 'T9R7V']);
$order->getResponse();

$order->dispatchOrder([
    'order_id' => 'T9R7V'
    'products' => [
        'sku' => 'EXP-143-33S',
        'opc' => 'PN8JV6',
        'quantity' => 1
    ],
    'tracking' => [
        "tracking_id": '123',
        "supplier_name": "dhl",
        "number": "456",
        "url": "https://example.com/path-to-resource/"
    ]
]);
$order->getResponse();

$order->cancelOrder([
    'order_id' => 'T9R7V',
    'order_cancellation_reason_id' => 1,
    'cancel_order_additional_info' => 'Out of stock'
]);
$order->getResponse();

$order->refundOrder([
    'order_id' => 'T9R7V',
    'order_refund_reason_id' => 1,
    'seller_note' => 'Customer Return',
    'customer_note' => 'Item return received - Thank you!'
]);
$order->getResponse();

$order->getTrackingProviders();
$order->getResponse();
```

### Product

![Submit](https://i.snipboard.io/aKWnhQ.jpg)

#### Product

```php

$product = new Product($auth->getToken());
$product->createProduct($insertArray);
$product->getResponse();

$product->testCreateProductByBatch($insertArray);
$product->getResponse();

$product->updateProduct($updateArray); // single array
$product->getResponse();

$product->updateProductByBatch($updateArray); // array of array
$product->getResponse();

$product->getProduct([
    'query' => 'test',
    'field' => 'name'
]);
$product->getResponse();

```

#### Product Listing

```php
$listing = new Listing($auth->getToken());

$listing->getListing(
    ['last_created' => 'asc'],
    ['sku' => 'test']
);
$listing->getResponse();

$listing->updateListingBySku([
    [
        "sku" => "EXP-143-33S",
        "price" => 126.34,
        "stock" => 125,
        "boost_marketing_commission" => 14.83
    ],
    [
        "sku" => "EXP-143-33L",
        "price" => 126.34,
        "stock" => 125,
        "boost_marketing_commission" => 14.83
    ],
]);
$listing->getResponse();

$listing->deleteListingBySku([
    "EXP-143-33S",
    "EXP-144-33L"
]);
$listing->getResponse();

$listing->createListing(
    'P5ZVSFF',
    [[
        "sku" => "EXP-143-33S",
        "group_sku" => "bar",
        "boost_marketing_commission" => 2.98
    ]]
);
$listing->getResponse();

$listing->createListingByBatch(
    [[
        "opc" => "PN8JV6",
        "condition" => "poor",
        "price" => 9.99,
        "stock" => 8,
        "delivery_weight" => 16,
        "handling_time" => 125,
        "free_returns" => "true",
        "warranty" => 7
    ]]
);
$listing->getResponse();

$listing->getWinningListing([
    "EXP-143-33S",
    "EXP-144-33L"
]);
$listing->getResponse();
```

#### Queue

```php
$queue = new Queue($auth->getToken());

$queue->getQueue([
    'queue_ids' => '123,456',
    'status' => 'pending'
]);
$queue->getResponse();

$queue->getQueueById(123);
$queue->getResponse();
```

### Seller

#### Seller

```php
$seller = new Seller($auth->getToken());
$seller->getSellerById(123);
$seller->getResponse();
```

#### Seller Deliveries

```php
$sellerDelivery = new Delivery($auth->getToken());
$sellerDelivery->getDelivery();
$sellerDelivery->getResponse();
```

#### Seller Entities

```php
$sellerEntity = new Entity($auth->getToken());

$sellerEntity->getEntity();
$sellerEntity->getResponse();

$sellerEntity->getEntityById(123);
$sellerEntity->getResponse();
```

### Sites

```php
$site = new Site($auth->getToken());

$site->getSite([
    'name' => 'test'
]);
$site->getResponse();

$site->getSiteById(123);
$site->getResponse();
```