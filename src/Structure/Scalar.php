<?php

namespace izucken\asn1\Structure;

class Scalar extends AbstractStructuralElement
{
    public int $identifier;
    /**
     * @var callable|array|null
     */
    public $values;

    function __construct(int $identifier, $values = null)
    {
        $this->identifier = $identifier;
        $this->values = $values;
    }
}