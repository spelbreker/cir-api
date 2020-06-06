<?php

namespace patrickDevelopment\cir\client;

use GuzzleHttp\Client;
use patrickDevelopment\cir\builder\Builder;
use patrickDevelopment\cir\builder\helper\helper;
use patrickDevelopment\cir\client\factory\soapPackageFactory;

class cirClient
{

    protected $client;
    protected $response;
    protected $builder;
    private $username = '';
    private $password = '';

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
        $this->client = new Client([
            'headers' => [
                "Content-Type" => " application/soap+xml"
            ]
        ]);
    }

    public function setAuthentication(string $username, string $password): void
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function execute()
    {

        $soapPackage = soapPackageFactory::create($this->builder, $this->username, $this->password);

        $this->response = $this->client->post('https://webservice.rechtspraak.nl/cir.asmx', [
            'body' => $soapPackage->saveXML(),

        ]);


        if ($this->response->getStatusCode() === 200) {
            return $this->getResults($this->response->getBody());
        }

    }

    public function getResults(string $body)
    {
        $xmlDoc = new \DOMDocument('1.0', 'UTF-8');
        $xmlDoc->loadXML($body);

        $results = helper::xml_to_array($xmlDoc)['soap:Envelope']['soap:Body'];

        if (array_key_exists('searchUndertakingResponse', $results)) {
            return $this->extractPublications($results);
        }

        return $results;


    }

    public function extractPublications(array $results)
    {
        $results = $this->searchkeyInArray($results, 'publicatieLijst');
        unset($results['publicatieLijst']['@attributes']); //remove extration date
        return $results['publicatieLijst'];
    }

    public function searchkeyInArray(array $array, string $key)
    {
        $results = array();

        // if it is array
        if (is_array($array)) {

            // if array has required key and value
            // matched store result
            if (isset($array[$key])) {
                return $results[] = $array;
            }

            // Iterate for each element in array
            foreach ($array as $subarray) {

                // recur through each element and append result
                $results = array_merge($results,
                    $this->searchkeyInArray($subarray, $key));
            }

            return $results;
        }
    }


}
