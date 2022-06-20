## MVOLA PHP

<span style="margin:auto; text-align: center;width: 500px;display:block">
<img width="350" src="https://i.ibb.co/MgKHQR7/mail.png" alt="mvola api" />
	
[![Latest Stable Version](http://poser.pugx.org/dadapas/mvola-php/v)](https://packagist.org/packages/dadapas/mvola-php) [![Total Downloads](http://poser.pugx.org/dadapas/mvola-php/downloads)](https://packagist.org/packages/dadapas/mvola-php) [![Latest Unstable Version](http://poser.pugx.org/dadapas/mvola-php/v/unstable)](https://packagist.org/packages/dadapas/mvola-php) [![License](http://poser.pugx.org/dadapas/mvola-php/license)](https://packagist.org/packages/dadapas/mvola-php) [![PHP Version Require](http://poser.pugx.org/dadapas/mvola-php/require/php)](https://packagist.org/packages/dadapas/mvola-php)
</span>

PHP class wrap up Madagascar mobile money request as mvola.

This package facilitate these features for you:

 * Handle authentification token and expires token
 * Generate automaticly correlation ID and reference for payement
 * Request handler 

## Getting started

The suggested installation method is via [composer](https://getcomposer.org/):

```sh
composer require dadapas/mvola-php
```

To get started

```php
require_once __DIR__ . "/vendor/autoload.php";
use MVolaphp\Telma as MVola;

$credentials = array(
	// Customer id
	'client_id'		=> '<customer_id>',
	// Customer secret
	'client_secret'		=> '<customer_secret>',
	// The merchant number
	'merchant_number'	=> '0343500003',
	// Set true to production
	'production'	  	=> false,
	// company_name
	'partner_name'		=> "company_name",
	// Set the lang
	'lang'				=> 'MG'
);

// Path to cache that is enable to read and write
$cache = __DIR__.'/cache';

try {

	$mvola = new MVola(CREDENTIALS, $cache);

	// ...
} catch (MVolaphp\Exception $e) {

	echo $e->getMessage().PHP_EOL;

	var_dump($e->getData());
}

```

Sending money to merchent like
```php
use MVolaphp\Money;
use MVolaphp\Objects\{Phone, PayIn, KeyValue};
...

$payDetails = new PayIn();

// Amount of 1000 ar or arivo ariary
$money = new Money('MGA', 5000);

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
// $meta->add('fc', 'USD');
// $meta->add('amountFc', 1);

// Add metadata information
$payDetails->metadata = $meta;

// Put callback url
$mvola->setCallbackUrl("https://example.com/mycallback")

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
* [Getting Started](docs/getting-started.md)
* [Merchant](docs/merchent.md)

## Contributing

Please read the [CONTRIBUTING.md](CONTRIBUTING.md) contents if you wish to help out!


## LICENCE
MIT Licence
