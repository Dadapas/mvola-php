# MOBILE MONEY
PHP class wrap up Madagascar mobile money request as mvola.

[![Build Status](https://travis-ci.org/dadapas/mobile-money.svg?branch=main)](https://travis-ci.org/dadapas/mobile-money)
[![Code Coverage](https://codecov.io/gh/dadapas/mobile-money/branch/master/graph/badge.svg)](https://codecov.io/gh/dadapas/mobile-money/branch/master)
[![Dependency Status](https://www.versioneye.com/package/php--dadapas--mobile-money/badge.svg)](https://www.versioneye.com/package/php--dadapas--mobile-money)

[![Latest Stable Version](https://poser.pugx.org/dadapas/mobile-money/v/stable.png)](https://packagist.org/packages/dadapas/mobile-money)
[![Latest Unstable Version](https://poser.pugx.org/dadapas/mobile-money/v/unstable.png)](https://packagist.org/packages/dadapas/mobile-money)


## Getting started

The suggested installation method is via [composer](https://getcomposer.org/):

```sh
composer require dadapas/mobile-money
```

To get started

```php
require_once __DIR__ . "/vendor/autoload.php";
use MobileMoney\Telma as MVola;

define('CREDENTIALS',[
	// Customer id
	'client_id'			=> '<customer_id>',
	// Customer secret
	'client_secret'		=> '<customer_secret>',
	// The merchant number
	'merchant_number'	=> '0343500003',
	// Set true to production
	'production'	  	=> false,
	// company_name
	'partner_name'		=> "company_name",
]);

// Path to cache that is enable to read and write
$cache = '/path/to/cache';

try {

	$mvola = new MVola(CREDENTIALS, $cache);

	// ...
} catch (MobileMoney\Exception | MobileMoney\Exceptions\HttpRequestException $e) {

	echo $e->getMessage();
}

```

Sending money to merchent like
```php
use MobileMoney\Money;
use MobileMoney\Objects\{Phone, PayIn, KeyValue};
...

$payDetails = new PayIn();

// Amount of 1000 ar or arivo ariary
$money = new Money('MGA', 1000);

$payDetails->amount = $money;

// User to retreive the amount
$debit = new KeyValue();
$debit->addPairObject(new Phone("0343500004"));
$payDetails->debitParty = $debit;

// Credited party not obligatoire if has been set in options

// $merchant = new KeyValue();
// $merchant->addPairObject(new Phone("0343500004"));
// $payDetails->creditParty = $merchant;

// Set description text
$payDetails->descriptionText = "Test payement";

$meta = new KeyValue();
$meta->add('partnerName', "Company name");
$meta->add('fc', 'USD');
$meta->add('amountFc', 1);

// Add metadata information
$payDetails->metadata = $meta;

// Make a payement 	
$response = $mvola->payIn($payDetails);

print_r($response);
```

For testing only msisdn `034 35 000 03` and `034 35 000 04` work
Use real number for production

## Support
This repository is support to all php project, **Symfony**, **Laravel**, **Codeigniter**, **Wordpress**, ..., and so on

## Documentation

To read the documentation is in
* [Getting Started](docs/getting-started)
* [Merchant](docs/merchent.md)

## Contributing

Please read the [CONTRIBUTING.md](CONTRIBUTING.md) contents if you wish to help out!


## LICENCE
MIT Licence