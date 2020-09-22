<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Primitive;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;
use izucken\asn1\Structures\Struct;

class Certificate extends AbstractModuleEnvelope
{
    public TBSCertificate $tbsCertificate;
    public AlgorithmIdentifier $signatureAlgorithm;
    public string $signature;

    public function schema(): StructuralElement
    {
        return new Sequence([
            "tbsCertificate"     => new Struct(TBSCertificate::class),
            "signatureAlgorithm" => new Struct(AlgorithmIdentifier::class),
            "signature"          => new Primitive(Identifier::BITSTRING),
        ]);
    }
}