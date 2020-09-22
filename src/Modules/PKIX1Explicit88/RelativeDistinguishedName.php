<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\SetOf;
use izucken\asn1\Structures\StructuralElement;
use izucken\asn1\Structures\Struct;

class RelativeDistinguishedName extends AbstractModuleEnvelope
{
    /**
     * @var AttributeTypeAndValue[]
     */
    public $value;

    public function schema(): StructuralElement
    {
        return new SetOf(new Struct(AttributeTypeAndValue::class));
    }
}