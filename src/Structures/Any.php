<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use izucken\asn1\Context;

class Any extends AbstractStructuralElement
{
    function __construct()
    {
    }

    public function parse(ASNObject $asn, Context $ctx)
    {
        $ctx->evaluate($asn);
    }
}