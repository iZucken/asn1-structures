<?php

namespace izucken\asn1\Modules;

use izucken\asn1\Structures\StructuralElement;

interface ModuleEnvelope
{
    public function schema(): StructuralElement;
}