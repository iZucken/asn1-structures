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
        $implicit = $ctx->castStructuralElement($this->implicit);
        return $this->envelopeRoot($asn, $implicit, $ctx);
    }

    public function envelopeRoot(ASNObject $asn, StructuralElement $as, Context $ctx)
    {
        switch (get_class($as)) {
            case Sequence::class:
            case SequenceOf::class:
                return $ctx->parse(new \FG\ASN1\Universal\Sequence(...$asn->getContent()), $this->implicit);
            case Set::class:
                return $ctx->parse(new \FG\ASN1\Universal\Set(...$asn->getContent()), $this->implicit);
            case Primitive::class:
            case Any::class:
                return $asn->getContent();
            case Struct::class:
                return $this->envelopeRoot($asn, (new $as)->schema(), $ctx);
            default:
                throw new \Exception("Not implemented implicit cast $as");
        }
    }
}