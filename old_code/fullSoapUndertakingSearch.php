<?php

$username = '';
$password = '';

/* create a dom document with encoding utf8 */
$XMLDoc = new DOMDocument('1.0', 'UTF-8');
$XMLDoc->preserveWhiteSpace = false;
$XMLDoc->formatOutput = false;

// SOAP ENVELOPE ELEMENT AND ATTRIBUTES
$soap = $XMLDoc->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'soap:Envelope');
$XMLDoc->appendChild($soap);

$soap->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cir', 'http://www.rechtspraak.nl/namespaces/cir01');
$soap->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:soap', 'http://www.w3.org/2003/05/soap-envelope');
$soap->setAttributeNS('http://www.w3.org/2000/xmlns/' , 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');


//
// SOAP HEADER
//
$header = $XMLDoc->createElement('soap:Header');

$header->setAttribute('xmlns:wsa', 'http://schemas.xmlsoap.org/ws/2004/08/addressing');
$header->setAttribute('xmlns:wsrm', 'http://docs.oasis-open.org/ws-rx/wsrm/200702');

// WSSE security
$wsse = $XMLDoc->createElement('wsse:Security');
$wsse->setAttribute('xmlns:wsse','http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd');
$wsse->setAttribute('xmlns:wsu','http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd');

$UsernameToken = $XMLDoc->createElement('wsse:UsernameToken');
$UsernameToken->appendChild( $XMLDoc->createElement('wsse:Username',$username));
$UsernameToken->appendChild($XMLDoc->createElement('wsse:Password',$password));

$wsse->appendChild($UsernameToken);

//attach to header
$header->appendChild($wsse);

// WSRM HEADER
$Sequence = $XMLDoc->createElement('wsrm:Sequence');
$Sequence->appendChild( $XMLDoc->createElement('wsrm:Identifier','soap:Sender'));
$Sequence->appendChild($XMLDoc->createElement('wsrm:MessageNumber','1'));
//attach to header
$header->appendChild($Sequence);

$header->appendChild( $XMLDoc->createElement('wsa:Action','http://www.rechtspraak.nl/namespaces/cir01/CIRSoap/searchUndertakingRequest'));
$header->appendChild( $XMLDoc->createElement('wsa:To','https://webservice.rechtspraak.nl/cir.asmx'));

//attach to envelope
$soap->appendChild($header);

//
// SOAP BODY
//
$body = $XMLDoc->createElement('soap:Body');

// XML CONTENT
$rootElement = $XMLDoc->createElement('cir:searchUndertaking');
$rootNode = $body->appendChild($rootElement);

$rootNode->appendChild($XMLDoc->createElement('cir:postalCode','2404HH'));
$rootNode->appendChild($XMLDoc->createElement('cir:houseNumber','99'));
// attach to document
$soap->appendChild($body);

Header('Content-type: text/xml');
/* get the xml printed */
echo $xml = $XMLDoc->saveXML();

//var_dump(htmlentities($xml));

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://webservice.rechtspraak.nl/cir.asmx",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $xml,
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/soap+xml"
    ),

));

$response = curl_exec($curl);
echo '----------------------------';
curl_close($curl);
echo $response;
