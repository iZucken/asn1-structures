<?php

namespace izucken\asn1\Structure;

class SequenceOf extends AbstractStructuralElement
{
    private StructuralElement $of;

    function __construct(StructuralElement $of)
    {
        $this->of = $of;
    }
}