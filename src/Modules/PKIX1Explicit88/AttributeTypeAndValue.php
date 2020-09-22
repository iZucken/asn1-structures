<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Any;
use izucken\asn1\Structures\Primitive;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;

class AttributeTypeAndValue extends AbstractModuleEnvelope
{
    public $type;
    public $value;

    public function schema(): StructuralElement
    {
        return new Sequence([
            'type'  => new Primitive(Identifier::OBJECT_IDENTIFIER),
            'value' => new Any(),
        ]);
    }

    public function getType(): string
    {
        return (string)$this->asn[0]->getContent();
    }
}