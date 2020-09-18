<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\ASNObject;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class Name extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        // ::= CHOICE -- only one possibility for now
        $this->expectStructure(RDNSequence::class, $asn);
    }

    function getRdnSequence()
    {
        return (new RDNSequence)->setAsn($this->asn);
    }

    public function getOidMap(): array
    {
        return $this->getRdnSequence()->getOidMap();
    }
}