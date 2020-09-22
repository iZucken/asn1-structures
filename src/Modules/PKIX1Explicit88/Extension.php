<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Any;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;

class Extension extends AbstractModuleEnvelope
{
    public string $extnID;
    public bool $critical = false;
    public $extnValue;

    public function schema(): StructuralElement
    {
        return new Sequence([
            "extnID" => Identifier::OBJECT_IDENTIFIER,
            "critical" => new Sequence\Option(Identifier::BOOLEAN),
            "extnValue" => new Any, // DER encoding of an ASN.1 DEFINED BY extnID
        ]);
    }
}