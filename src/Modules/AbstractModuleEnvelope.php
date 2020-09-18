<?php

namespace izucken\asn1\Modules;

use Exception;
use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;

abstract class AbstractModuleEnvelope implements ModuleEnvelope
{
    protected ?ASNObject $asn = null;
    protected array $errors = [];

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

    protected function isContextTag(?ASNObject $asn, $tag)
    {
        return !empty($asn)
            && Identifier::isContextSpecificClass($asn->getType())
            && $tag === Identifier::getTagNumber($asn->getType());
    }

    protected function expect($expected, $message = null)
    {
        if (true !== $expected) {
            $this->error(true, $expected, $message);
        }
    }

    protected function expectIn($expected, $received, $message = null)
    {
        if (!in_array($received->getContent(), $expected)) {
            $this->error($expected, $received->getContent(), $message);
        }
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
            $this->expectStructureList($expected, $received, $message);
        } else {
            $this->expectAsnList($expected, $received, $message);
        }
    }

    protected function expectAsnList($expected, $received, $message)
    {
        foreach ($received as $asn) {
            $this->expectType($expected, $asn, $message);
        }
    }

    protected function expectStructureList($expected, $received, $message)
    {
        foreach ($received as $asn) {
            $this->expectStructure($expected, $asn, $message);
        }
    }

    protected function error($expected, $received, $message = null)
    {
        $shortFqcn = preg_replace("#^(\w+\\\\)+(\w+)$#", "$2", static::class);
        $expectedType = gettype($received);
        $receivedType = gettype($received);
        $expectedMessage = $message ?? "$expected ($expectedType)";
        $this->errors[] = "$shortFqcn: expected $expectedMessage, received $received ($receivedType)";
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}