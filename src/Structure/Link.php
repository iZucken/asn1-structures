<?php

namespace izucken\asn1\Structure;

class Link extends AbstractStructuralElement
{
    public string $name;
    public StructuralElement $element;

    function __construct(string $name, StructuralElement $element)
    {
        $this->element = $element;
        $this->name = $name;
    }
}