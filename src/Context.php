<?php

namespace izucken\asn1;

use FG\ASN1\ASNObject;
use izucken\asn1\Structures\Primitive;
use izucken\asn1\Structures\StructuralElement;
use izucken\asn1\Structures\Struct;

class Context
{
    public function getAttribute($name)
    {
        return $this->envelope->$name ?? null;
    }

    public function assert($condition, $message = null)
    {
        // todo: что если инверировать в броски из структур, и ловить проблему внутри контекста...
        if (!$condition) {
            $path = join(".", $this->stack);
            throw new StructuralError("$path: " . ($message ?? "Assertion failed"));
        }
    }

    private $stack = [];

    function descend($name)
    {
        $this->stack[] = $name;
    }

    function ascend()
    {
        array_pop($this->stack);
    }

    function shortFqcn($class)
    {
        $class = get_class($class);
        $chunks = explode("\\", $class);
        return array_pop($chunks);
    }

    /**
     * @param StructuralElement|string|int $structure
     * @return StructuralElement
     */
    public function castStructuralElement($structure): StructuralElement {
        if (is_int($structure)) {
            return new Primitive($structure);
        } elseif (is_string($structure)) {
            return new Struct($structure);
        }
        return $structure;
    }

    /**
     * @param ASNObject                    $asn
     * @param StructuralElement|string|int $structure
     */
    public function parse(ASNObject $asn, $structure)
    {
        $structure = $this->castStructuralElement($structure);
        $this->descend($this->shortFqcn($structure));
        $value = $structure->parse($asn, $this);
        $this->ascend();
        return $value;
    }
}