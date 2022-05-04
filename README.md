# MOBILE MONEY
PHP class wrap up Madagascar mobile money request as mvola.

## For installation
`composer require dadapas/mobile-money`


```php
require_once __DIR__ . "/vendor/autoload.php";

use MobileMoney\Telma as Mvola;

$credentials = [
	'username'	=> 'myusername',
	'password'  => 'mypass'
];

$cache = '/path/to/cache';

try {
	$mvola = new Mvola($credentials, $cache);

	// ...
} catch (MobileMoney\Exception $e) {
	
	echo $e->getMessage();
}

```
As credentials there is more thant using `api key` like
```php
$credentials = [
	'apiKey'	=> '...'
];
```

# Examples

There is more way of building mobile money for