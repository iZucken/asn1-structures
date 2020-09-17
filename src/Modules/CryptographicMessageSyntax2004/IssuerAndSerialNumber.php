<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Modules\PKIX1Explicit88\Name;

class IssuerAndSerialNumber extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectEqual(Identifier::SEQUENCE, $asn->getType());
        $this->expectStructure(Name::class, $asn[0]);
        $this->expectEqual(Identifier::INTEGER, $asn[1]->getType());
    }

    function getIssuer(): Name
    {
        return (new Name)->setAsn($this->asn[0]);
    }

    function getSerialNumber(): string
    {
        return $this->asn[1]->getContent();
    }
}