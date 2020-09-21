<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\ASNObject;
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
    function validate(ASNObject $asn)
    {
        $this->expectEqual(Identifier::SEQUENCE, $asn->getType());
        $this->expectStructure(Version::class, $asn[0]);
        $this->expectEqual(Identifier::INTEGER, $asn[1]->getType());
        $this->expectStructure(AlgorithmIdentifier::class, $asn->getContent()[2]);
        $this->expectStructure(Name::class, $asn[3]);
        $this->expectStructure(Validity::class, $asn->getContent()[4]);
        $this->expectStructure(Name::class, $asn[5]);
        $this->expectStructure(SubjectPublicKeyInfo::class, $asn->getContent()[6]);
    }

    function schema(): StructuralElement
    {
        return new Sequence([
            'version'              => new Option(new Explicit(0, Identifier::INTEGER)), // default v1 (0)
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

    function getVersion(): Version
    {
        return (new Version)->setAsn($this->asn[0]);
    }

    function getSerialNumber(): string
    {
        return $this->asn[1]->getContent();
    }

    function getIssuer(): Name
    {
        return (new Name)->setAsn($this->asn[3]);
    }

    function getValidity(): Validity
    {
        return (new Validity)->setAsn($this->asn[4]);
    }

    function getSubject(): Name
    {
        return (new Name)->setAsn($this->asn[5]);
    }
}