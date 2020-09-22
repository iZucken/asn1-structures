<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use izucken\asn1\Context;

class MappedByAttribute extends AbstractStructuralElement
{
    public string $by;
    public array $map;
    private bool $nullable;

    function __construct(string $by, array $map, bool $nullable = false)
    {
        $this->by = $by;
        $this->map = $map;
        $this->nullable = $nullable;
    }

    function parse(ASNObject $asn, Context $ctx)
    {
        $attributeValue = $ctx->getAttribute($this->by);
        // todo:
        if (empty($attributeValue) && $this->nullable) {
            if ($this->nullable) {
                return null;
            } else {
                $ctx->assert(false);
            }
        } else {
            if (empty($this->map[$attributeValue])) {
                return null;
            } else {
                return $ctx->parse($asn, $this->map[$attributeValue]);
            }
        }
    }
}