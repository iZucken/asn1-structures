<?php

namespace izucken\asn1\Structure;

class SetOf extends AbstractStructuralElement
{
    private StructuralElement $of;

    function __construct(StructuralElement $of)
    {
        $this->of = $of;
    }
}