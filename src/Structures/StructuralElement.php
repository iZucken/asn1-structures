<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use izucken\asn1\Context;

interface StructuralElement
{
    function parse(ASNObject $asn, Context $ctx);
}