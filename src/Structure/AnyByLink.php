<?php

namespace izucken\asn1\Structure;

class AnyByLink extends AbstractStructuralElement
{
    public string $by;
    public array $map;

    function __construct(string $by, array $map)
    {
        $this->by = $by;
        $this->map = $map;
    }
}