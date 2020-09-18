<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class SubjectKeyIdentifier extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectContext(0, $asn);
        $this->expectEqual(Identifier::OCTETSTRING, $asn->getContent()[0]->getType());

        //SubjectKeyIdentifier ::= OCTET STRING
    }
}