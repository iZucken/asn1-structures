<?php

namespace izucken\asn1\Structure;

class Sequence extends AbstractStructuralElement
{
    public array $sequence;

    function __construct(array $sequence)
    {
        $this->sequence = $sequence;
    }
}