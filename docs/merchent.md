## Merchent

 * [To get started](getting-started.md)

For production set `production` options to true or
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
// xx2x2f8z-s5sb-qo1j-ng50-m1o7q6hu8hnf
$correlationID = "correlation_id";

$statusDetails = $mvola->status($correlationID);
print_r($statusDetails);
```