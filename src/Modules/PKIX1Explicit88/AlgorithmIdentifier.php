<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class AlgorithmIdentifier extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectEqual(Identifier::SEQUENCE, $asn->getType());
//        $this->expectEqual(2, count($asn->getContent()));
        $this->expectEqual(Identifier::OBJECT_IDENTIFIER, $asn[0]->getType());
    }

    function getAlgorithm(): string
    {
        return $this->asn[0]->getContent();
    }

    // contains a value of the type registered for use with the algorithm object identifier value
    function getParameters(): ?ASNObject
    {
        return $this->asn[1];
    }
}