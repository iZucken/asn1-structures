<?php

namespace izucken\asn1\Structures\Sequence;

use izucken\asn1\Structures\StructuralElement;

class Option
{
    /**
     * @var int|StructuralElement|string
     */
    private $element;

    /**
     * @param StructuralElement|string|int $element
     */
    function __construct($element)
    {
        $this->element = $element;
    }

    /**
     * @return int|StructuralElement|string
     */
    public function getElement()
    {
        return $this->element;
    }
}