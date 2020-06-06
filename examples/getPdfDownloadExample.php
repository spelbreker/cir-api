<?php
require '../vendor/autoload.php';

$client = new \patrickDevelopment\cir\client\CirClient(new \patrickdevelopment\cir\builder\GetCaseWithReports());
$client->fetchPdfDownload('09_dha_20_118_F_V_02');
