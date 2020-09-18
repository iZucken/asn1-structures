<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\ASNObject;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class SignedAttributes extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectContext(0, $asn);
        $this->expect(count($asn->getContent()) > 0);
        $this->expectContextOf(0, Attribute::class, $asn);
    }

    /**
     * @return Attribute[]
     */
    function getAttributes(): array
    {
        $list = [];
        foreach ($this->asn->getContent() as $sequence) {
            $list[] = (new Attribute())->setAsn($sequence);
        }
        return $list;
    }
}