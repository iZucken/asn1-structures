<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class AttributeTypeAndValue extends AbstractModuleEnvelope
{
    public function validate(ASNObject $asn)
    {
        $this->expectEqual(Identifier::SEQUENCE, $asn->getType());
        $this->expectEqual(Identifier::OBJECT_IDENTIFIER, $asn[0]->getType(), "first item is OBJECT_IDENTIFIER");
    }

    public function getType(): string
    {
        return (string)$this->asn[0]->getContent();
    }

    // ::= ANY -- DEFINED BY AttributeType
    public function getValue(): string
    {
        return (string)$this->asn[1]->getContent();
    }
}