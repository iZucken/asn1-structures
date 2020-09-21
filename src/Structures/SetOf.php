<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Context;

class SetOf extends AbstractStructuralElement
{
    /**
     * @var int|StructuralElement|string
     */
    private $of;

    /**
     * @param StructuralElement|string|int $of
     */
    function __construct($of)
    {
        $this->of = $of;
    }

    public function parse(ASNObject $asn, Context $ctx)
    {
        $ctx->assert($asn->getType() === Identifier::SET);
        foreach ($asn->getContent() as $element) {
            $ctx->parse($element, $this->of);
        }
    }
}