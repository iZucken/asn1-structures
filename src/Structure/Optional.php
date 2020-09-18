<?php

namespace izucken\asn1\Structure;

class Optional extends AbstractStructuralElement
{
    public StructuralElement $option;

    function __construct(StructuralElement $option)
    {
        $this->option = $option;
    }
}