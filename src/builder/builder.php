<?php

namespace patrickDevelopment\cir\builder;

interface Builder
{


    public function get(): \DOMElement;

    public function getRootObjectName(): string;
    public function getUri(): string;
}
