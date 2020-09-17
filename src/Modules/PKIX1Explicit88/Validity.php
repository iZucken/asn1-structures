<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use izucken\asn1\Modules\AbstractModuleEnvelope;

class Validity extends AbstractModuleEnvelope
{
    function getNotBefore(): \DateTimeInterface {
        return $this->asn[0]->getContent();
    }
    function getNotAfter(): \DateTimeInterface {
        return $this->asn[1]->getContent();
    }
}