<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class AlgorithmIdentifier extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expect(Identifier::SEQUENCE, $asn->getType());
    }

    function getAlgorithm (): string {
        return $this->asn->getContent()[0]->getContent();
    }

    // contains a value of the type registered for use with the algorithm object identifier value
    function getParameters (): ?ASNObject {
        return $this->asn->getContent()[1];
    }
}