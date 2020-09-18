<?php

namespace izucken\asn1\Structure;

class Set extends AbstractStructuralElement
{
    private string $name;
    private array $set;

    function __construct(string $name, array $set)
    {
        $this->name = $name;
        $this->set = $set;
    }
}