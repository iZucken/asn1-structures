<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Modules\PKIX1Explicit88\AlgorithmIdentifier;

class SignerInfo extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectType(Identifier::SEQUENCE, $asn);
        $offset = 0;
        $this->expectType(Identifier::INTEGER, $asn[$offset++]);
        $this->expectStructure(SignerIdentifier::class, $asn[$offset++]);
        $this->expectStructure(AlgorithmIdentifier::class, $asn[$offset++]);
        if ($this->isContextTag($asn[$offset], 0)) {
            $this->expectStructure(SignedAttributes::class, $asn[$offset++]);
        }
        $this->expectStructure(AlgorithmIdentifier::class, $asn[$offset++]);
        $this->expectType(Identifier::OCTETSTRING, $asn[$offset++]);
        //unsignedAttrs [1] IMPLICIT UnsignedAttributes OPTIONAL
    }

    //CMSVersion ::= INTEGER  { v0(0), v1(1), v2(2), v3(3), v4(4), v5(5) }
    function getVersion(): int
    {
        return $this->asn[0]->getContent();
    }

    function getSid(): SignerIdentifier
    {
        return (new SignerIdentifier)->setAsn($this->asn[1]);
    }

    function getSignedAttributes(): ?SignedAttributes
    {
        if ($this->isContextTag($this->asn[3], 0)) {
            return (new SignedAttributes)->setAsn($this->asn[3]);
        }
        return null;
    }

    function getDigestAlgorithm(): AlgorithmIdentifier
    {
        return (new AlgorithmIdentifier)->setAsn($this->asn[2]);
    }
}