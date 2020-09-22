<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Choice;
use izucken\asn1\Structures\Implicit;
use izucken\asn1\Structures\StructuralElement;

class SignerIdentifier extends AbstractModuleEnvelope
{
    public string $choice;
    public ?IssuerAndSerialNumber $issuerAndSerialNumber;
    public ?string $subjectKeyIdentifier;

    function schema(): StructuralElement
    {
        return new Choice([
            "issuerAndSerialNumber" => IssuerAndSerialNumber::class,
            "subjectKeyIdentifier"  => new Implicit(0, Identifier::OCTETSTRING),
        ]);
    }
}