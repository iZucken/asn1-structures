<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Any;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\SetOf;
use izucken\asn1\Structures\StructuralElement;

class Attribute extends AbstractModuleEnvelope
{
    public string $type;
    public $values;

    function schema(): StructuralElement
    {
        return new Sequence([
            "type" => Identifier::OBJECT_IDENTIFIER,
            "values" => new SetOf(new Any),
        ]);
    }
}