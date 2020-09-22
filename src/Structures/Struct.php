<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use izucken\asn1\Context;

class Struct extends AbstractStructuralElement
{
    public string $of;

    function __construct(string $of)
    {
        $this->of = $of;
    }

    public function parse(ASNObject $asn, Context $ctx)
    {
        $instance = new $this->of;
        $schema = $instance->schema();
        $parsed = $ctx->parse($asn, $schema);
        switch (get_class($schema)) {
            case Sequence::class:
            case Set::class:
                foreach ($parsed as $attribute => $value) {
                    $instance->$attribute = $value;
                }
                break;
            case Choice::class:
                foreach ($parsed as $attribute => $value) {
                    $instance->$attribute = $value;
                    $instance->choice = $attribute;
                }
                break;
            default:
                $instance->value = $parsed;
        }
        return $instance;
    }
}