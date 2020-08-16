# OnBuy Php SDK

OnBuy - UK Online Marketplace & Amazon Alternative

Unofficial PHP SDK to interact with OnBuy API

![phpcs](https://github.com/DominicWatts/OnBuySDK/workflows/phpcs/badge.svg)

![PHPCompatibility](https://github.com/DominicWatts/OnBuySDK/workflows/PHPCompatibility/badge.svg)

![PHPStan](https://github.com/DominicWatts/OnBuySDK/workflows/PHPStan/badge.svg)

![PHPUnit](https://github.com/DominicWatts/OnBuySDK/workflows/PHPUnit/badge.svg)

## Install Instructions

`composer require dominicwatts/onbuysdk`

There is a composer archive at:

[https://packagist.org/packages/dominicwatts/onbuysdk](https://packagist.org/packages/dominicwatts/onbuysdk)

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

### Brands

```php
$brand = new Brand($auth->getToken());
$brand->getBrand('keyword', 'asc', 10, 0);
$brand->getBrandById(123);
```

### Categories

#### Categories

```php
$category = new Category($auth->getToken());

$category->getCategory([
    'name' => 'test'
]);

$category->getCategory([
    'name' => 'test',
    'category_type_id' => 1 // department
]);

$category->getCategoryById(13490);
```

#### Categories Features

```php
$categoryFeature = new Feature($auth->getToken());
$categoryFeature->getFeatureById(13490);
```

#### Categories Technical Details

```php
$categoryTechnical = new Technical($auth->getToken());
$categoryTechnical->getTechnicalDetailById(13490);
$categoryTechnical->getGroupById(13490, 125);
```

#### Categories Variants

```php
$categoryVariant = new Variant($auth->getToken());
$categoryVariant->getVariantId(13490);
```

### Commission

```php
$commission = new Commission($auth->getToken());
$commission->getTier();
$commission->getTierById(13490, 125);
```

### Condition

```php
$condition = new Condition($auth->getToken());
$condition->getCondition();
```

### Order

```php
$order = new Order($auth->getToken());

$order->getOrder([
    'status' => 'awaiting_dispatch',
    'previously_exported' => 0
], 'asc');

$order->getOrderById('T9R7V');

$order->dispatchOrder(['order_id' => 'T9R7V']);

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

$order->cancelOrder([
    'order_id' => 'T9R7V'.
    'order_cancellation_reason_id' => 1,
    'cancel_order_additional_info' => 'Out of stock'
]);

$order->refundOrder([
    'order_id' => 'T9R7V'.
    'order_refund_reason_id' => 1,
    'seller_note' => 'Customer Return',
    'customer_note' => 'Item return received - Thank you!'
]);

$order->getTrackingProviders();
```

### Product

![Submit](https://i.snipboard.io/aKWnhQ.jpg)

#### Product

```php

$product = new Product($auth->getToken());
$product->createProduct($insertArray);
$product->updateProduct($updateArray); // single array
$product->updateProductByBatch($updateArray); // array of array

$product->getProduct([
    'query' => 'test',
    'field' => 'name'
]);

```

#### Product Listing

```php
$listing = new Listing($auth->getToken());

$listing->getListing();

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
]));

$listing->deleteListingBySku([
    "EXP-143-33S",
    "EXP-144-33L"
]);

$listing->createListing(
    'P5ZVSFF',
    [[
        "sku" => "EXP-143-33S",
        "group_sku" => "bar",
        "boost_marketing_commission" => 2.98
    ]]
);

$listing->createListingByBatch(
    [
        "opc" => "PN8JV6",
        "condition" => "poor",
        "price" => 9.99,
        "stock" => 8,
        "delivery_weight" => 16,
        "handling_time" => 125,
        "free_returns" => "true",
        "warranty" => 7
    ]
);

$listing->getWinningListing([
    "EXP-143-33S",
    "EXP-144-33L"
]);
```

#### Queue

```php
$queue = new Queue($auth->getToken());

$queue->getQueue([
    'queue_ids' => '123,456',
    'status' => 'pending'
]);

$queue->getQueueById(123);
```

### Seller

#### Seller

```php
$seller = new Seller($auth->getToken());
$seller->getSellerById(123);
```

#### Seller Deliveries

```php
$sellerDelivery = new Delivery($auth->getToken());
$sellerDelivery->getDelivery();
```

#### Seller Entities

```php
$sellerEntity = new Entity($auth->getToken());
$sellerEntity->getEntity();
$sellerEntity->getEntityById(123);
```

### Sites

```php
$site = new Site($auth->getToken());

$site->getSite([
    'name' => 'test'
]);

$site->getSiteById(123);
```