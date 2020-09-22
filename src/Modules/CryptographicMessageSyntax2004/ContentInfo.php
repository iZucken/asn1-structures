<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\MappedByAttribute;
use izucken\asn1\Structures\Explicit;
use izucken\asn1\Structures\Primitive;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;
use izucken\asn1\Structures\Struct;

class ContentInfo extends AbstractModuleEnvelope
{
    public string $contentType;
    /**
     * @var SignedData
     */
    public $content;

    function schema(): StructuralElement
    {
        return new Sequence([
            'contentType' => new Primitive(Identifier::OBJECT_IDENTIFIER), // ["1.2.840.113549.1.7.2"]
            'content'     => new Explicit(0, new MappedByAttribute('contentType', [
                "1.2.840.113549.1.7.2" => new Struct(SignedData::class),
            ]))
        ]);
    }
}