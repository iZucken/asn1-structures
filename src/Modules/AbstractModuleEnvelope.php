<?php

namespace izucken\asn1\Modules;

use Exception;
use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;

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

    public function setAsn(ASNObject $asn): self
    {
        $this->validate($asn);
        $this->asn = $asn;
        return $this;
    }

    public function expect($expected, $message = null)
    {
        if (true !== $expected) {
            $this->error(true, $expected, $message);
        }
    }

    public function isContextTag($asn, $tag)
    {
        return Identifier::isContextSpecificClass($asn->getType())
            && $tag === Identifier::getTagNumber($asn->getType());
    }

    public function expectContext($expected, $received, $message = null)
    {
        if (!Identifier::isContextSpecificClass($received->getType())) {
            $this->error(true, false, $message ?? "context-specific class");
        }
        $tagNumber = Identifier::getTagNumber($received->getType());
        if ($expected !== $tagNumber) {
            $this->error($expected, $tagNumber, $message ?? "context tag $expected");
        }
    }

    public function expectEqual($expected, $received, $message = null)
    {
        if ($expected !== $received) {
            $this->error($expected, $received, $message);
        }
    }

    public function expectStructure($expected, $received)
    {
        $expectedStructure = new $expected;
        $expectedStructure->validate($received);
    }

    public function expectContextOf($context, $expected, $received)
    {
        $this->expectContext($context, $received);
        $this->expectStructureList($expected, $received->getContent());
    }

    public function expectListOf($list, $expected, $received)
    {
        $this->expectEqual($list, $received->getType());
        $this->expectStructureList($expected, $received->getContent());
    }

    public function expectSetOf($expected, $received)
    {
        $this->expectEqual(Identifier::SET, $received->getType());
        $this->expectStructureList($expected, $received->getContent());
    }

    public function expectStructureList($expected, $received)
    {
        foreach ($received as $asn) {
            $this->expectStructure($expected, $asn);
        }
    }

    public function error($expected, $received, $message = null)
    {
        $shortFqcn = preg_replace("#^(\w+\\\\)+(\w+)$#", "$2", static::class);
        $expectedType = gettype($received);
        $receivedType = gettype($received);
        $expectedMessage = $message ?? "$expected ($expectedType)";
        throw new Exception("$shortFqcn: expected $expectedMessage, received $received ($receivedType)");
    }

    public static function tryEnvelope(ASNObject $asn): ?self
    {
        try {
            $instance = new static;
            $instance->setAsn($asn);
            return $instance;
        } catch (Exception $exception) {
            return null;
        }
    }
}