<?php


namespace izucken\asn1\Modules\PKIX1Explicit88;


use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class TBSCertificate extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectEqual(Identifier::SEQUENCE, $asn->getType());
        $this->expect($asn->getContent() >= 7);
        $this->expectStructure(Version::class, $asn[0]);
        $this->expectEqual(Identifier::INTEGER, $asn[1]->getType());
        $this->expectStructure(AlgorithmIdentifier::class, $asn->getContent()[2]);
        $this->expectStructure(Name::class, $asn[3]);
        $this->expectStructure(Validity::class, $asn->getContent()[4]);
        $this->expectStructure(Name::class, $asn[5]);
        $this->expectStructure(SubjectPublicKeyInfo::class, $asn->getContent()[6]);
    }

    // [0] Version
    function getVersion(): Version
    {
        return (new Version)->setAsn($this->asn->getContent()[0]);
    }

    function getSerialNumber(): string
    {
        return $this->asn->getContent()[1]->getContent();
    }

    function getSignature(): AlgorithmIdentifier
    {
        return (new AlgorithmIdentifier)->setAsn($this->asn->getContent()[2]);
    }

    function getIssuer(): Name
    {
        return (new Name)->setAsn($this->asn->getContent()[3]);
    }

    function getValidity(): Validity
    {
        return (new Validity)->setAsn($this->asn->getContent()[4]);
    }

    function getSubject(): Name
    {
        return (new Name)->setAsn($this->asn->getContent()[5]);
    }

    function getSubjectPublicKeyInfo(): SubjectPublicKeyInfo
    {
        return (new SubjectPublicKeyInfo)->setAsn($this->asn->getContent()[6]);
    }

    // [1]  IMPLICIT UniqueIdentifier OPTIONAL, -- If present, version MUST be v2 or v3
    function issuerUniqueID()
    {
        return $this->asn->getContent()[7];
    }

    // [2]  IMPLICIT UniqueIdentifier OPTIONAL, -- If present, version MUST be v2 or v3
    function subjectUniqueID()
    {
        return $this->asn->getContent()[8];
    }

    // [3]  Extensions OPTIONAL -- If present, version MUST be v3
    function extensions()
    {
        return $this->asn->getContent()[9];
    }
}