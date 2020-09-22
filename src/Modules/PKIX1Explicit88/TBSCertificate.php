<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Explicit;
use izucken\asn1\Structures\Implicit;
use izucken\asn1\Structures\Primitive;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\Sequence\Option;
use izucken\asn1\Structures\SequenceOf;
use izucken\asn1\Structures\StructuralElement;
use izucken\asn1\Structures\Struct;

class TBSCertificate extends AbstractModuleEnvelope
{
    public int $version;
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
            'version'              => new Option(new Explicit(0, new Primitive(Identifier::INTEGER))), // default v1 (0)
            'serialNumber'         => new Primitive(Identifier::INTEGER),
            'signature'            => new Struct(AlgorithmIdentifier::class),
            'issuer'               => new Struct(Name::class),
            'validity'             => new Struct(Validity::class),
            'subject'              => new Struct(Name::class),
            'subjectPublicKeyInfo' => new Struct(SubjectPublicKeyInfo::class),
            // If present, version MUST be v2 or v3
            'issuerUniqueID'       => new Option(new Implicit(1, new Primitive(Identifier::BITSTRING))),
            // If present, version MUST be v2 or v3
            'subjectUniqueID'      => new Option(new Implicit(2, new Primitive(Identifier::BITSTRING))),
            // If present version MUST be v3
            'extensions'           => new Option(new Explicit(3, new SequenceOf(new Struct(Extension::class)))),
        ], true);
    }
}