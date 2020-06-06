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
        $this->client = new Client();
    }

    public function setAuthentication(string $username, string $password): void
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getPdf(string $kenmerk) {
        $result = $this->client->get('https://insolventies.rechtspraak.nl/Services/VerslagenService/getPdf/'.$kenmerk);
        return $result->getBody();

    }

    public function getPdfDownload(string $kenmerk) {
        $result = $this->getPdf($kenmerk);

        header("Content-type: application/octet-stream");
        header("Content-disposition: attachment;filename=$kenmerk.pdf");

        echo $result;

    }

    public function execute()
    {

        $soapPackage = soapPackageFactory::create($this->builder, $this->username, $this->password);

        $this->response = $this->client->post('https://webservice.rechtspraak.nl/cir.asmx', [
            'body' => $soapPackage->saveXML(),
            'headers' => [
                "Content-Type" => " application/soap+xml"
            ]

        ]);


        if ($this->response->getStatusCode() === 200) {
            return $this->getResults($this->response->getBody());
        }

    }

    public function getResults(string $body)
    {
        $xmlDoc = new \DOMDocument('1.0', 'UTF-8');
        $xmlDoc->loadXML($body);

        $results = helper::xml_to_array($xmlDoc)['soap:Envelope']['soap:Body'];;

        if ($this->searchkeyInArray($results, 'publicatieLijst')) {
            return $this->extractPublications($results);
        } elseif (!empty($this->searchkeyInArray($results, 'inspubWebserviceInsolvente'))) {
            return $this->searchkeyInArray($results, 'inspubWebserviceInsolvente')['inspubWebserviceInsolvente'];
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
        $search_results = array();

        // if it is array
        if (is_array($array)) {

            // if array has required key and value
            // matched store result
            if (isset($array[$key])) {
                return $array;
            }

            // Iterate for each element in array
            foreach ($array as $subarray) {

                if (is_array($subarray)) {
                    // recur through each element and append result
                    $search_results = array_merge($search_results,
                        $this->searchkeyInArray($subarray, $key));
                }
            }

            return $search_results;
        }
    }


}
