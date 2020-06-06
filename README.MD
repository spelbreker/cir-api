# CIR (Centraal insulventieregister) API

## Installation

With [composer](http://packagist.org), add:

```bash
$ composer require patrickdevelopment/cir
```

## Usage

### search undertaking
```php
$builder = new \patrickDevelopment\cir\builder\SearchUndertaking();

$builder->setHouseNumber(99);
$builder->setPostalCode('2404HH');

$client = new \patrickDevelopment\cir\client\CirClient($builder);
$client->setAuthentication('username', 'password');
print_r($client->fetchResults());
```

### search get case with reports
```php
$builder = new \patrickDevelopment\cir\builder\GetCaseWithReports();
$builder->setPublicationNumber('09.dha.20.118.F.1300.1.20');

$client = new \patrickDevelopment\cir\client\CirClient($builder);
$client->setAuthentication('username', 'password');
print_r($client->fetchResults());
```

### get (PDF) reports
```php
$client = new \patrickDevelopment\cir\client\CirClient(new patrickDevelopment\cir\builder);
$client->fetchPdfDownload('09_dha_20_118_F_V_02');
```

## Author

CIR API has been created by Patrick Brouwer and is currently maintained by the developers at [Patrick Development](https://patrickdevelopment.nl).
