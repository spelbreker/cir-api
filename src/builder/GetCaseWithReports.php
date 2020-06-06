<?php

namespace patrickdevelopment\cir\builder;

use patrickDevelopment\cir\builder\Builder;
use DOMDocument;

class GetCaseWithReports implements Builder
{


    /**
     * @var string
     */
    private $publicationNumber = null;

    public function __construct()
    {
    }

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

        $classPath = explode('\\',get_class($this));
        $rootName = lcfirst($classPath[count($classPath)-1]);

        $rootElement = $XMLDoc->createElement('cir:'.$rootName);

        foreach (get_object_vars($this) as $key => $object) {
            $rootElement->appendChild(
                $XMLDoc->createElement('cir:'.$key, $object)
            );
        }


        return $rootElement;
    }
}

