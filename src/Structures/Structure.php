<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use izucken\asn1\Context;

class Structure extends AbstractStructuralElement
{
    public string $of;

    function __construct(string $of)
    {
        $this->of = $of;
    }

    public function parse(ASNObject $asn, Context $ctx)
    {
//        $ctx->envelope($asn, $this->of);
        // todo: maps an envelope definition
    }
}