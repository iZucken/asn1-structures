<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use izucken\asn1\Modules\AbstractModuleEnvelope;

class Name extends AbstractModuleEnvelope
{
    //Name ::= CHOICE -- only one possibility for now
    function getRdnSequence () {
        return (new RDNSequence)->setAsn($this->asn);
    }
}