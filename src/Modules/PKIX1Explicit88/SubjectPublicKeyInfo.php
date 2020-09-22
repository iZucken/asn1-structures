<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;

class SubjectPublicKeyInfo extends AbstractModuleEnvelope
{
    public AlgorithmIdentifier $algorithm;
    public string $subjectPublicKey;

    public function schema(): StructuralElement
    {
        return new Sequence([
            "algorithm"        => AlgorithmIdentifier::class,
            "subjectPublicKey" => Identifier::BITSTRING,
        ]);
    }
}