<?php

require __DIR__ . '/vendor/autoload.php';

$builder = new \patrickDevelopment\cir\builder\SearchUndertaking();

$builder->setHouseNumber(99);
$builder->setPostalCode('2404HH');

$client = new \patrickDevelopment\cir\client\CirClient($builder);
$client->setAuthentication('username', 'password');
print_r($client->fetchResults());
