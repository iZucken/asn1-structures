<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Explicit;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;

class EncapsulatedContentInfo extends AbstractModuleEnvelope
{
    public string $contentType;
    public ?string $content;

    function schema(): StructuralElement
    {
        return new Sequence([
            'contentType' => Identifier::OBJECT_IDENTIFIER,
            'content'     => new Sequence\Option(new Explicit(0, Identifier::OCTETSTRING)),
        ]);
    }
}