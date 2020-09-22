<?php

namespace izucken\asn1;

use FG\ASN1\ASNObject;
use izucken\asn1\Modules\ModuleEnvelope;
use izucken\asn1\Structures\StructuralElement;
use izucken\asn1\Structures\Struct;

class Context
{
    private ModuleEnvelope $envelope;

    public function __construct(ModuleEnvelope $envelope)
    {
        $this->envelope = $envelope;
    }

    public function getEnvelope()
    {
        return $this->envelope;
    }

    public function evaluateStructure(ASNObject $asn)
    {
        $schema = $this->envelope->schema();
        $this->parse($asn, $schema);
        return $this;
    }

    public function setAttribute($name, $value): self
    {
        $this->envelope->$name = $value;
        return $this;
    }

    public function getAttribute($name)
    {
        return $this->envelope->$name ?? null;
    }

    public function assert($condition, $message = null)
    {
        if (!$condition) {
            throw new \Exception($message ?? "Assertion failed");
        }
    }

    public function evaluate($value)
    {
        // todo: set value on current operated node
    }

    /**
     * @param ASNObject                    $asn
     * @param StructuralElement|string|int $structure
     */
    function parse(ASNObject $asn, $structure)
    {
        if ($structure instanceof Struct) {
            // todo: evaluate subtree
//            $structure->parse($asn, $this);
        } else {
            $structure->parse($asn, $this);
        }
    }
}