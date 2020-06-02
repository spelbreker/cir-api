<?php

namespace patrickDevelopment\cir\client;

use GuzzleHttp\Client;
use patrickDevelopment\cir\builder\Builder;
use patrickDevelopment\cir\builder\helper\helper;
use patrickDevelopment\cir\client\factory\soapPackageFactory;

class cirClient
{

    protected $client;
    protected $builder;
    private $username = '';
    private $password = '';

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
        $this->client = new Client([
            'headers' => [
                "Content-Type" =>" application/soap+xml"
            ]
        ]);
    }

    public function setAuthentication(string $username, string $password): void
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function execute() {

        $soapPackage = soapPackageFactory::create($this->builder,$this->username,$this->password);

        $r = $this->client->post( 'https://webservice.rechtspraak.nl/cir.asmx', [
            'body' => $soapPackage->saveXML(),

        ]);



        return $this->getResults($r->getBody());

    }

    public function getResults(string $body ) {
        $xmlDoc = new \DOMDocument('1.0', 'UTF-8');
        $xmlDoc->loadXML($body);

        return helper::xml_to_array($xmlDoc)['soap:Envelope']['soap:Body'];
    }

}
