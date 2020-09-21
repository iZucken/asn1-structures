<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Context;

class Implicit extends AbstractStructuralElement
{
    private int $tag;
    /**
     * @var int|StructuralElement|string
     */
    private $implicit;

    /**
     * @param int                          $tag
     * @param StructuralElement|string|int $implicit
     */
    function __construct(int $tag, $implicit)
    {
        $this->tag = $tag;
        $this->implicit = $implicit;
    }

    public function parse(ASNObject $asn, Context $ctx)
    {
        $ctx->assert(Identifier::isContextSpecificClass($asn->getType()));
        $ctx->assert(Identifier::getTagNumber($asn->getType()) === $this->tag);
        // todo: the root is assumed
        $ctx->parse($asn, $this->implicit);
    }
}