<?php

namespace izucken\asn1\Structure;

class Structure extends AbstractStructuralElement
{
    public string $of;

    function __construct(string $of)
    {
        $this->of = $of;
    }
}