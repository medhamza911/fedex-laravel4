FedEx Laravel
=================
This is a fork of Arkitecht/fedex-laravel, package modified for backward compability to support Laravel 4.*.


Quick Installation
------------------
You can install the package most easily through composer

#### Laravel 4.x
```
composer require krsman/fedex-laravel4
```

Using it in your project
------------------
**Add the service provider to your config/app.php**

```php
<?php

...
'providers' => array(

		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\Session\CommandsServiceProvider',

...
		'Krsman\FedEx\Laravel\Providers\FedExServiceProvider',
    ),
?>
```

**Add the Facade to your config/app.php**

```php
<?php

...
    'aliases' => array(

        'FedEx'     => 'Krsman\FedEx\Laravel\Facades\FedEx'

    ),
?>
```

**Publish the config file**

```php artisan config:publish krsman/fedex-laravel4```

**Set up your environment**

Add values for the following setting keys in your /app/config/packages/krsman/fedex-laravel4/config.php

    'key'      => '',
    'password' => '',
    'account'  => '',
    'meter'    => '',
    'beta'     => false,

Example of Usage
------------------

**Get FedEx Rates**

```php
<?php

$rateRequest = FedEx::rateRequest();

$shipment = new \Arkitecht\FedEx\Structs\RequestedShipment();
$shipment->TotalWeight = new \Arkitecht\FedEx\Structs\Weight(\Arkitecht\FedEx\Enums\WeightUnits::VALUE_LB, $weight);

$shipment->Shipper = new \Arkitecht\FedEx\Structs\Party();
$shipment->Shipper->Address = new \Arkitecht\FedEx\Structs\Address(
    $shipper->address,
    $shipper->city,
    $shipper->state,
    $shipper->zip,
    null, 'US');

$shipment->Recipient = new \Arkitecht\FedEx\Structs\Party();
$shipment->Recipient->Address = new \Arkitecht\FedEx\Structs\Address(
    $recipient->address,
    $recipient->city,
    $recipient->state,
    $recipient->zip,
    null, 'US');

$lineItem = new \Arkitecht\FedEx\Structs\RequestedPackageLineItem();
$lineItem->Weight = new \Arkitecht\FedEx\Structs\Weight(\Arkitecht\FedEx\Enums\WeightUnits::VALUE_LB, $weight);
$lineItem->GroupPackageCount = 1;
$shipment->PackageCount = 1;

$shipment->RequestedPackageLineItems = [
    $lineItem
];

$rateRequest->Version = FedEx::rateService()->version;

$rateRequest->setRequestedShipment($shipment);

$rate = FedEx::rateService();

$response = $rate->getRates($rateRequest);

$rates = [];

if ($response->HighestSeverity == 'SUCCESS') {
    foreach ($response->RateReplyDetails as $rate) {
        $rates[$rate->ServiceType] = $rate->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount;
    }
}
