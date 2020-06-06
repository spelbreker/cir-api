<?php

namespace patrickDevelopment\cir\client\factory;

use DOMDocument;
use patrickDevelopment\cir\builder\Builder;

class soapPackageFactory
{

    public static function create(Builder $builder, string $username, string $password): \DOMDocument
    {
        /* create a dom document with encoding utf8 */
        $XMLDoc = new \DOMDocument('1.0', 'UTF-8');
        $XMLDoc->preserveWhiteSpace = false;
        $XMLDoc->formatOutput = false;

        // SOAP ENVELOPE ELEMENT AND ATTRIBUTES
        $soap = $XMLDoc->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'soap:Envelope');
        $XMLDoc->appendChild($soap);

        $soap->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cir', 'http://www.rechtspraak.nl/namespaces/cir01');
        $soap->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:soap', 'http://www.w3.org/2003/05/soap-envelope');
        $soap->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        //attach to envelope
        $soap->appendChild(self::createHeader($XMLDoc, $builder, $username, $password));
        $soap->appendChild(self::createBody($XMLDoc, $builder));

        return $XMLDoc;
    }


    protected static function createHeader(DOMDocument $XMLDoc, Builder $builder, string $username, string $password): \DOMElement
    {
        $header = $XMLDoc->createElement('soap:Header');

        $header->setAttribute('xmlns:wsa', 'http://schemas.xmlsoap.org/ws/2004/08/addressing');
        $header->setAttribute('xmlns:wsrm', 'http://docs.oasis-open.org/ws-rx/wsrm/200702');

        // WSSE security
        $wsse = $XMLDoc->createElement('wsse:Security');
        $wsse->setAttribute('xmlns:wsse', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd');
        $wsse->setAttribute('xmlns:wsu', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd');

        $UsernameToken = $XMLDoc->createElement('wsse:UsernameToken');
        $UsernameToken->appendChild($XMLDoc->createElement('wsse:Username', $username));
        $UsernameToken->appendChild($XMLDoc->createElement('wsse:Password', $password));

        $wsse->appendChild($UsernameToken);

        //attach to header
        $header->appendChild($wsse);

        // WSRM HEADER
        $Sequence = $XMLDoc->createElement('wsrm:Sequence');
        $Sequence->appendChild($XMLDoc->createElement('wsrm:Identifier', 'soap:Sender'));
        $Sequence->appendChild($XMLDoc->createElement('wsrm:MessageNumber', '1'));
        //attach to header
        $header->appendChild($Sequence);

        $header->appendChild(
            $XMLDoc->createElement('wsa:Action', 'http://www.rechtspraak.nl/namespaces/cir01/CIRSoap/searchUndertakingRequest')
        );
        $header->appendChild($XMLDoc->createElement('wsa:To', 'https://webservice.rechtspraak.nl/cir.asmx'));

        return $header;
    }

    protected static function createBody(DOMDocument $XMLDoc, Builder $builder): \DOMElement
    {
        $body = $XMLDoc->createElement('soap:Body');

        $body->appendChild(
            $XMLDoc->importNode($builder->get(), true)
        );

//        // XML CONTENT
//        $rootElement = $XMLDoc->createElement('cir:searchUndertaking');
//        $rootNode = $body->appendChild($rootElement);
//
//        $rootNode->appendChild($XMLDoc->createElement('cir:postalCode', '2401GK'));
//        $rootNode->appendChild($XMLDoc->createElement('cir:houseNumber', '58'));

        return $body;
    }

    public static function getXML(DOMDocument$xmlDoc): string
    {
        return $xmlDoc->saveXML();
    }

}
