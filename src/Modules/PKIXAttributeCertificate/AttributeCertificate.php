<?php

namespace izucken\asn1\Modules\PKIXAttributeCertificate;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Modules\PKIX1Explicit88\AlgorithmIdentifier;
use izucken\asn1\Structures\Any;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;

class AttributeCertificate extends AbstractModuleEnvelope
{
    public function schema(): StructuralElement
    {
        return new Sequence([
            "acinfo" => new Any, // AttributeCertificateInfo
            "signatureAlgorithm" => AlgorithmIdentifier::class,
            "signatureValue" => Identifier::BITSTRING,
        ]);
    }
}