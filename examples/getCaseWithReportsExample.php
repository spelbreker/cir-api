<?php
require __DIR__ . '/vendor/autoload.php';

$builder = new \patrickDevelopment\cir\builder\GetCaseWithReports();
$builder->setPublicationNumber('09.dha.20.118.F.1300.1.20');

$client = new \patrickDevelopment\cir\client\CirClient($builder);
$client->setAuthentication('username', 'password');
print_r($client->fetchResults());
