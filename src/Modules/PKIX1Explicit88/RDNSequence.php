<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\SequenceOf;
use izucken\asn1\Structures\StructuralElement;
use izucken\asn1\Structures\Struct;

class RDNSequence extends AbstractModuleEnvelope
{
    /**
     * @var RelativeDistinguishedName[]
     */
    public $value;

    public function schema(): StructuralElement
    {
        return new SequenceOf(new Struct(RelativeDistinguishedName::class));
    }

    public function oidMap(): array
    {
        $map = [];
        foreach ($this->value as $rdn) {
            foreach ($rdn->value as $item) {
                $map[$item->type] = $item->value;
            }
        }
        return $map;
    }
}