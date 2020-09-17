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
        $this->expectEqual(Identifier::SEQUENCE, $asn->getType());
        $offset = 0;
        $this->expectEqual(Identifier::INTEGER, $asn[$offset++]->getType());
        $this->expectStructure(SignerIdentifier::class, $asn[$offset++]);
        $this->expectStructure(AlgorithmIdentifier::class, $asn[$offset++]);
        $type = $asn[$offset]->getType();
        if (
            Identifier::isContextSpecificClass($type) && Identifier::getTagNumber($type) === 0
        ) {
//            $this->expectStructure(SignedAttributes::class, $asn[$offset++]->getType());
            $offset++;
        }
        $this->expectStructure(AlgorithmIdentifier::class, $asn[$offset++]);
        $this->expectEqual(Identifier::OCTETSTRING, $asn[$offset++]->getType());
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

    function getDigestAlgorithm(): AlgorithmIdentifier
    {
        return (new AlgorithmIdentifier)->setAsn($this->asn[2]);
    }

    // todo: адекватная поддержка следования за опциональными атрибутами
}