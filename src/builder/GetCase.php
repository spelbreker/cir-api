<?php

namespace patrickdevelopment\cir\builder;

use patrickDevelopment\cir\builder\Builder;
use DOMDocument;

class GetCase implements Builder
{

    /**
     * @var string
     */
    private $publicationNumber = null;

    /**
     * @return string
     */
    public function getPublicationNumber(): string
    {
        return $this->publicationNumber;
    }

    /**
     * @param string $publicationNumber
     */
    public function setPublicationNumber(string $publicationNumber): void
    {
        $this->publicationNumber = $publicationNumber;
    }

    public function get(): \DOMElement
    {
        $XMLDoc = new DOMDocument('1.0', 'UTF-8');
        $XMLDoc->preserveWhiteSpace = false;
        $XMLDoc->formatOutput = false;

        $rootElement = $XMLDoc->createElement('cir:getCase');

        $rootElement->appendChild(
            $XMLDoc->createElement('cir:publicationNumber', $this->getPublicationNumber())
        );

        return $rootElement;
    }
}

