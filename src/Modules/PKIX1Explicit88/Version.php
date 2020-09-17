<?php


namespace izucken\asn1\Modules\PKIX1Explicit88;


use izucken\asn1\Modules\AbstractModuleEnvelope;

class Version extends AbstractModuleEnvelope
{
    //explicitly tagged
    //Version  ::=  INTEGER  {  v1(0), v2(1), v3(2)  }
    function getVersion(): int {
        return $this->asn->getContent()[0]->getContent();
    }
}