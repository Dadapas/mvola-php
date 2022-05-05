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

use MobileMoney\Telma as Mvola;

$credentials = [
	// Customer ID
	'client_id'		 	=> 'mysuperusername',
	// Customer Secret
	'client_secret'		=> 'mysupersecret',
	// Environement
	'isProduction'		=> false,
	// The shop number
	'shopNumber'		=> "0343500003"
];

$cache = '/path/to/cache';

try {
	// Initiation object
	$mvola = new Mvola($credentials, $cache);

	// ...
} catch (MobileMoney\Exception $e) {

	echo $e->getMessage();
}

```

Sending money to merchent like
```php

// Money object 
$money = new MobileMoney\Money('MGA', 1000);
$merchent = '0343500004';

// Sending 1000 Ar to 034 35 000 04
$mvola->send($money, $merchent);
```

For production set `isProduction` options to true
```php

$mvola->setProduction();

```

For testing only msisdn is `034 35 000 03` and `034 35 000 04`
Use for sender or se

## Contributing

Please read the [CONTRIBUTING.md](CONTRIBUTING.md) contents if you wish to help out!


## LICENCE
MIT Licence