<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Context;

class Primitive extends AbstractStructuralElement
{
    public int $identifier;

    function __construct(int $identifier)
    {
        $this->identifier = $identifier;
    }

    public function parse(ASNObject $asn, Context $ctx)
    {
        $ctx->assert(
            $this->identifier === $asn->getType(),
            "Expected " . Identifier::getShortName($this->identifier) . ", got " . Identifier::getShortName($asn->getType())
        );
        if ($this->identifier === Identifier::NULL) {
            return null;
        }
        return $asn->getContent();
    }
}