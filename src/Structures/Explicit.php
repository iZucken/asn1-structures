<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Context;

class Explicit extends AbstractStructuralElement
{
    public int $tag;
    /**
     * @var int|StructuralElement|string
     */
    public $explicit;

    /**
     * @param int                          $tag
     * @param StructuralElement|string|int $explicit
     */
    function __construct(int $tag, $explicit)
    {
        $this->tag = $tag;
        $this->explicit = $explicit;
    }

    public function parse(ASNObject $asn, Context $ctx)
    {
        $ctx->assert(Identifier::isContextSpecificClass($asn->getType()));
        $ctx->assert(Identifier::getTagNumber($asn->getType()) === $this->tag);
        $ctx->parse($asn->getContent()[0], $this->explicit);
    }
}