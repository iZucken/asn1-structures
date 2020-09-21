<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\MappedByAttribute;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;

class AlgorithmIdentifier extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectType(Identifier::SEQUENCE, $asn);
        $this->expectType(Identifier::OBJECT_IDENTIFIER, $asn->getContent()[0]);
    }

    function schema(): StructuralElement
    {
        return new Sequence([
            'algorithm'  => Identifier::OBJECT_IDENTIFIER,
            'parameters' => new Sequence\Option(new MappedByAttribute('algorithm', [])),
        ]);
    }

    function getAlgorithm(): string
    {
        return $this->asn[0]->getContent();
    }

    function getParameters(): ?ASNObject
    {
        return $this->asn[1];
    }
}