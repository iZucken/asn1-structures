<?php

namespace izucken\asn1\Structure;

class Choice extends AbstractStructuralElement
{
    /**
     * @var StructuralElement[]
     */
    public array $of;

    /**
     * @param StructuralElement[] $of
     */
    function __construct(array $of)
    {
        $this->of = $of;
    }
}