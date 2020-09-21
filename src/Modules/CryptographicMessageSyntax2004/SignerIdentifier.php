<?php


namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;


use FG\ASN1\ASNObject;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Choice;
use izucken\asn1\Structures\StructuralElement;

class SignerIdentifier extends AbstractModuleEnvelope
{
    public $issuerAndSerialNumber;
    public $subjectKeyIdentifier;

    function validate(ASNObject $asn)
    {
        $this->expectStructure(IssuerAndSerialNumber::class, $asn);
    }

    function schema(): StructuralElement
    {
        return new Choice([
            "issuerAndSerialNumber" => IssuerAndSerialNumber::class,
//            "subjectKeyIdentifier" => [0] SubjectKeyIdentifier ::= OCTET STRING,
        ]);
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