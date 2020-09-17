<?php


namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;


use FG\ASN1\ASNObject;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class SignerIdentifier extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        //SignerIdentifier ::= CHOICE {
        //issuerAndSerialNumber IssuerAndSerialNumber,
        //subjectKeyIdentifier [0] SubjectKeyIdentifier }
    }

    function getIssuerAndSerialNumber (): IssuerAndSerialNumber {
        return (new IssuerAndSerialNumber())->setAsn($this->asn);
    }
}