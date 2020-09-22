<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Primitive;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;

class Validity extends AbstractModuleEnvelope
{
    public $notBefore;
    public $notAfter;

    function schema(): StructuralElement
    {
        return new Sequence([
            'notBefore' => new Primitive(Identifier::UTC_TIME),
            'notAfter'  => new Primitive(Identifier::UTC_TIME),
        ]);
    }
}