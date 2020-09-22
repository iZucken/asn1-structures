<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Choice;
use izucken\asn1\Structures\Implicit;
use izucken\asn1\Structures\Primitive;
use izucken\asn1\Structures\StructuralElement;
use izucken\asn1\Structures\Struct;

class SignerIdentifier extends AbstractModuleEnvelope
{
    public ?IssuerAndSerialNumber $issuerAndSerialNumber;
    public ?string $subjectKeyIdentifier;

    function schema(): StructuralElement
    {
        return new Choice([
            "issuerAndSerialNumber" => new Struct(IssuerAndSerialNumber::class),
            "subjectKeyIdentifier"  => new Implicit(0, new Primitive(Identifier::OCTETSTRING)),
        ]);
    }
}