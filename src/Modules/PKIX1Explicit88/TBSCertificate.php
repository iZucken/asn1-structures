<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Explicit;
use izucken\asn1\Structures\Implicit;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\Sequence\Option;
use izucken\asn1\Structures\SequenceOf;
use izucken\asn1\Structures\StructuralElement;

class TBSCertificate extends AbstractModuleEnvelope
{
    public int $version = 0; // v1
    public string $serialNumber;
    public AlgorithmIdentifier $signature;
    public Name $issuer;
    public Validity $validity;
    public Name $subject;
    public SubjectPublicKeyInfo $subjectPublicKeyInfo;
//    $issuerUniqueID
//    $subjectUniqueID
//    $extensions

    function schema(): StructuralElement
    {
        return new Sequence([
            'version'              => new Option(new Explicit(0, Identifier::INTEGER)),
            'serialNumber'         => Identifier::INTEGER,
            'signature'            => AlgorithmIdentifier::class,
            'issuer'               => Name::class,
            'validity'             => Validity::class,
            'subject'              => Name::class,
            'subjectPublicKeyInfo' => SubjectPublicKeyInfo::class,
            // If present, version MUST be v2 or v3
            'issuerUniqueID'       => new Option(new Implicit(1, Identifier::BITSTRING)),
            // If present, version MUST be v2 or v3
            'subjectUniqueID'      => new Option(new Implicit(2, Identifier::BITSTRING)),
            // If present version MUST be v3
            'extensions'           => new Option(new Explicit(3, new SequenceOf(Extension::class))),
        ], true);
    }
}