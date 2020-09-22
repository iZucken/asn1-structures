<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Any;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;

class AttributeTypeAndValue extends AbstractModuleEnvelope
{
    public string $type;
    public $value;

    public function schema(): StructuralElement
    {
        return new Sequence([
            'type'  => Identifier::OBJECT_IDENTIFIER,
            'value' => new Any,
        ]);
    }
}