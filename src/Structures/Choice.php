<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use izucken\asn1\Context;

class Choice extends AbstractStructuralElement
{
    /**
     * @var StructuralElement|string|int[]
     */
    public array $of;

    /**
     * @param StructuralElement|string|int[] $of
     */
    function __construct(array $of)
    {
        $this->of = $of;
    }

    public function parse(ASNObject $asn, Context $ctx)
    {
        // choice shall have an explicit way to derive the chosen option
        foreach ($this->of as $element) {
            // $ctx-> $element, $asn ? $asn : next
        }
    }
}