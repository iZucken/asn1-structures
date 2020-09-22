<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\MappedByAttribute;
use izucken\asn1\Structures\Primitive;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;

class AlgorithmIdentifier extends AbstractModuleEnvelope
{
    public $algorithm;
    public $parameters;

    function schema(): StructuralElement
    {
        return new Sequence([
            'algorithm'  => new Primitive(Identifier::OBJECT_IDENTIFIER),
            'parameters' => new Sequence\Option(new MappedByAttribute('algorithm', [], true)),
        ]);
    }
}