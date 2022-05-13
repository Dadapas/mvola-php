# Contributing

 * Any contribution must provide tests for additional introduced conditions
 * Any un-confirmed issue needs a failing test case before being accepted
 * Pull requests must be sent from a new `dev/hotfix` branch, not from `main`.

## Installation

To install the project and run the tests, you need to clone it first:

```sh
$ git clone git://github.com/dadapas/mvola-php.git
```

Set up a branch for dev

```sh
$ git checkout -b dev/hotfix
```

You will then need to run a composer installation:

```sh
$ cd mvola-php
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar update
```

## Testing

The PHPUnit version to be used is the one installed as a dev dependency via composer:

```sh
$ ./vendor/bin/phpunit --verbose tests
```

Accepted coverage for new contributions is 80%. Any contribution not satisfying this requirement 
won't be merged.
