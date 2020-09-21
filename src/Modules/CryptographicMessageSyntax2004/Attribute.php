<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class Attribute extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectType(Identifier::SEQUENCE, $asn);
        $this->expectEqual(true, isset($asn[0]));
        $this->expectType(Identifier::OBJECT_IDENTIFIER, $asn[0]);
        $this->expectEqual(true, isset($asn[1]));
        $this->expectType(Identifier::SET, $asn[1]);
    }

    function getType (): string {
        return $this->asn[0]->getContent();
    }
}