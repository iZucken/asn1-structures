<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Modules\ModuleEnvelope;

class ContentInfo extends AbstractModuleEnvelope
{
    public $contentType = null;

    public static function tryEnvelope(\FG\ASN1\ASNObject $object): ?self
    {
        $envelope = new self;
        $envelope->asn = $object;
        //ContentInfo ::= SEQUENCE {
        //contentType ContentType,
        //content [0] EXPLICIT ANY DEFINED BY contentType }
    }
}