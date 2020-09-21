<?php

namespace izucken\asn1\Modules;

use izucken\asn1\Structures\StructuralElement;

interface ModuleEnvelope
{
    public function getAsn(): \FG\ASN1\ASNObject;
    public function setAsn(\FG\ASN1\ASNObject $asn);
    public function schema(): StructuralElement;
}