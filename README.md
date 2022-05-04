# MOBILE MONEY
PHP class wrap up Madagascar mobile money request as mvola.

For installation
`composer require dadapas/mobile-money`

```php
require_once __DIR__ . "/vendor/autoload.php";

$mvola = new MobileMoney\Telma as Mvola;

// Sending 50 mille ariary
$money = new MobileMoney\Money('MGA', 50000);
$mvola->sendMoney($money, '+261XXXXXXXXXXXX');

// Sending to agent
$mvola->receiveMoney($money, '+261XXXXXXXXXXXX')
```
# Examples

There is more way of building mobile money for

<b>Factory</b>

```php
try {
	$mvola = MobileMoney\Factory::create($options);
	// ...
} catch (MobileMoney\Exception $e) {
	echo $e->getMessage();
}
```