<?php

namespace patrickDevelopment\cir\builder;

use DOMDocument;
use patrickDevelopment\cir\builder\Builder;

class SearchUndertaking implements Builder
{

    /**
     * @var string
     */
    private $name = null;

    /**
     * @var string
     */
    private $commercialRegisterID = null;

    /**
     * @var string
     */
    private $postalCode = null;

    /**
     * @var int
     */
    private $houseNumber = null;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @return string
     */
    public function getCommercialRegisterID()
    {
        return $this->commercialRegisterID;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @return int
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $commercialRegisterID
     */
    public function setCommercialRegisterID(string $commercialRegisterID): void
    {
        $this->commercialRegisterID = $commercialRegisterID;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @param int $houseNumber
     */
    public function setHouseNumber(int $houseNumber): void
    {
        $this->houseNumber = $houseNumber;
    }


    public function get(): \DOMElement
    {

        $XMLDoc = new DOMDocument('1.0', 'UTF-8');
        $XMLDoc->preserveWhiteSpace = false;
        $XMLDoc->formatOutput = false;

        $rootElement = $XMLDoc->createElement('cir:searchUndertaking');

        if (!is_null($this->postalCode)) {
            $rootElement->appendChild(
                $XMLDoc->createElement('cir:postalCode', $this->getPostalCode())
            );
            $rootElement->appendChild(
                $XMLDoc->createElement('cir:houseNumber', $this->getHouseNumber())
            );
        }
        if (!is_null($this->commercialRegisterID)) {
            $rootElement->appendChild(
                $XMLDoc->createElement('cir:commercialRegisterID', $this->getCommercialRegisterID())
            );
        }
        if (!is_null($this->name)) {
            $rootElement->appendChild(
                $XMLDoc->createElement('cir:name', $this->getName())
            );
        }

        return $rootElement;
    }

}

