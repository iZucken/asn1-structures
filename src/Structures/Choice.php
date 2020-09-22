<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use izucken\asn1\Context;
use izucken\asn1\StructuralError;

class Choice extends AbstractStructuralElement
{
    /**
     * @var StructuralElement[]
     */
    public array $of;

    /**
     * @param StructuralElement[] $of
     */
    function __construct(array $of)
    {
        $this->of = $of;
    }

    public function parse(ASNObject $asn, Context $ctx)
    {
        foreach ($this->of as $variant => $element) {
            try {
                return [$variant => $ctx->parse($asn, $element)];
            } catch (StructuralError $exception) {
            }
        }
        $ctx->assert(false, "No choice options satisfied the input");
        return null;
    }
}