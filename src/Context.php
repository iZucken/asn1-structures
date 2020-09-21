<?php

namespace izucken\asn1;

use FG\ASN1\ASNObject;
use izucken\asn1\Modules\ModuleEnvelope;
use izucken\asn1\Structures\Primitive;
use izucken\asn1\Structures\StructuralElement;
use izucken\asn1\Structures\Structure;

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
        if (is_string($structure)) {
            $structure = new Structure($structure);
        } elseif (is_int($structure)) {
            $structure = new Primitive($structure);
        }
        $structure->parse($asn, $this);
    }
}