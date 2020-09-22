<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Modules\PKIX1Explicit88\Name;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;

class IssuerAndSerialNumber extends AbstractModuleEnvelope
{
    public Name $issuer;
    public string $serialNumber;

    function schema(): StructuralElement
    {
        return new Sequence([
            'issuer' => Name::class,
            'serialNumber' => Identifier::INTEGER,
        ]);
    }
}