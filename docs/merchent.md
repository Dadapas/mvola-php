## Merchent

For production set `isProduction` options to true
```php
// Set to production for real money
$mvola->setProduction();

```

To pay merchent just 

```php
// Callback url
$mvola->setCallbackUrl("http://example.com/callback");

```

The language is MG by default to change it

```php
// Change to FR
$mvola->setUpLangue("FR");
```
## Transation 
Want to get the merchant transaction ID

```php

$transID = "transactionid";

// get transaction
$transaction = $mvola->transaction($transID);
print_r( $transaction );

```

## Status
To get the status by correlation ID

```php

$correlationID = "correlationID";
$statusDetails = $mvola->status($statId);
print_r($statusDetails);
```