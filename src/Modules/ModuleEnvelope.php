<?php

namespace izucken\asn1\Modules;

interface ModuleEnvelope
{
    public function getAsn(): \FG\ASN1\ASNObject;
    public function setAsn(\FG\ASN1\ASNObject $asn);
    public static function tryEnvelope(\FG\ASN1\ASNObject $object): ?self;
}