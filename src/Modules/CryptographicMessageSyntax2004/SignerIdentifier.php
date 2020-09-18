<?php


namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;


use FG\ASN1\ASNObject;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class SignerIdentifier extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectStructure(IssuerAndSerialNumber::class, $asn);
//        $this->expectStructure(SubjectKeyIdentifier::class, $asn);
        // subjectKeyIdentifier [0] SubjectKeyIdentifier
    }

    function getIssuerAndSerialNumber(): IssuerAndSerialNumber
    {
        return (new IssuerAndSerialNumber)->setAsn($this->asn);
    }

    function getSubjectKeyIdentifier(): SubjectKeyIdentifier
    {
        return (new SubjectKeyIdentifier)->setAsn($this->asn);
    }
}