<?php

namespace izucken\asn1\Modules;

use Exception;
use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Structures\Any;
use izucken\asn1\Structures\StructuralElement;

abstract class AbstractModuleEnvelope implements ModuleEnvelope
{
    protected ?ASNObject $asn = null;

    public function getAsn(): ASNObject
    {
        return $this->asn;
    }

    public function validate(ASNObject $asn)
    {
    }

    public function schema(): StructuralElement
    {
        return new Any();
    }

    public function setAsn(ASNObject $asn): self
    {
        $this->asn = $asn;
        return $this;
    }

    protected function isContextTag(?ASNObject $asn, $tag)
    {
        return !empty($asn)
            && Identifier::isContextSpecificClass($asn->getType())
            && $tag === Identifier::getTagNumber($asn->getType());
    }

    protected function expectContext($expected, $received, $message = null)
    {
        if (!Identifier::isContextSpecificClass($received->getType())) {
            $this->error(true, false, $message ?? "context-specific class");
        }
        $tagNumber = Identifier::getTagNumber($received->getType());
        if ($expected !== $tagNumber) {
            $this->error($expected, $tagNumber, $message ?? "context tag $expected");
        }
    }

    protected function expectType($expected, ASNObject $received, $message = null)
    {
        if ($expected !== $received->getType()) {
            $this->error($expected, $received, $message);
        }
    }

    protected function expectEqual($expected, $received, $message = null)
    {
        if ($expected !== $received) {
            $this->error($expected, $received, $message);
        }
    }

    protected function expectStructure($expected, $received, $message = null)
    {
        $expectedStructure = new $expected;
        $expectedStructure->validate($received);
    }

    protected function expectContextOf($context, $expected, $received, $message = null)
    {
        $this->expectContext($context, $received);
        $this->expectTypeList($expected, $received->getContent());
    }

    protected function expectListOf($listType, $expected, $received)
    {
        $this->expectEqual($listType, $received->getType());
        $this->expectTypeList($expected, $received->getContent());
    }

    protected function expectTypeList($expected, $received, $message = null)
    {
        if (class_exists($expected)) {
            foreach ($received as $asn) {
                $this->expectStructure($expected, $asn, $message);
            }
        } else {
            foreach ($received as $asn) {
                $this->expectType($expected, $asn, $message);
            }
        }
    }

    protected function error($expected, $received, $message = null)
    {
        $shortFqcn = preg_replace("#^(\w+\\\\)+(\w+)$#", "$2", static::class);
        $expectedType = gettype($received);
        $receivedType = gettype($received);
        if (is_array($expected)) {
            $expected = join(",", array_values($expected));
        }
        if (is_array($received)) {
            $received = join(",", array_values($received));
        }
        $expectedMessage = $message ?? "$expected ($expectedType)";
        $message = "$shortFqcn: expected $expectedMessage, received $received ($receivedType)";
        throw new \Exception($message);
    }
}