<?php

namespace izucken\asn1\Modules;

use Exception;
use FG\ASN1\ASNObject;

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

    public function expect($expected, $received)
    {
        if ($expected !== $received) {
            $this->error($expected, $received);
        }
    }

    public function expectStructure($expected, $received)
    {
        (new $expected)->validate($received);
    }

    public function error($expected, $received)
    {
        throw new Exception(preg_replace("#^(\w+\\\\)+(\w+)$#","$2",static::class) . ": expected $expected, received $received");
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