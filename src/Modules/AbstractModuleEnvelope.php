<?php

namespace izucken\asn1\Modules;

use Exception;
use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Structures\Any;
use izucken\asn1\Structures\StructuralElement;

abstract class AbstractModuleEnvelope implements ModuleEnvelope
{
    public function schema(): StructuralElement
    {
        return new Any;
    }
}