<?php

namespace patrickDevelopment\cir\builder;

use DOMDocument;
use Phpro\SoapClient\Type\RequestInterface;
use Phpro\SoapClient\Type\ResultInterface;
use patrickDevelopment\cir\builder\Builder;

class SearchUndertaking implements Builder
{

    /**
     * @var string
     */
    private $uri = 'searchUndertaking';

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
     *
     * @var string $name
     * @var string $commercialRegisterID
     * @var string $postalCode
     * @var int $houseNumber
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

        $rootElement->appendChild(
            $XMLDoc->createElement('cir:postalCode',$this->getPostalCode() )
        );
        $rootElement->appendChild(
            $XMLDoc->createElement('cir:houseNumber',$this->getHouseNumber())
        );

        return $rootElement;
    }

    public function getRootObjectName(): string
    {
        // TODO: Implement getRootObjectName() method.
    }

    public function getUri(): string
    {
        // TODO: Implement getUri() method.
    }
}

