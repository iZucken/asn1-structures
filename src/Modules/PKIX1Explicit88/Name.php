<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Choice;
use izucken\asn1\Structures\StructuralElement;
use izucken\asn1\Structures\Struct;

class Name extends AbstractModuleEnvelope
{
    public string $choice;
    public ?RDNSequence $rdnSequence;

    public function schema(): StructuralElement
    {
        return new Choice([
            "rdnSequence" => new Struct(RDNSequence::class),
        ]);
    }
}