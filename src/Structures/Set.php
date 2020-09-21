<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use izucken\asn1\Context;

class Set extends AbstractStructuralElement
{
    private array $set;

    function __construct(array $set)
    {
        $this->set = $set;
    }

    function parse(ASNObject $asn, Context $ctx)
    {
        // todo: like SEQUENCE but unordered
    }
}