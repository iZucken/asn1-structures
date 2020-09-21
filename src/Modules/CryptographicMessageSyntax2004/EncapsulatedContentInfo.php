<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Explicit;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;

class EncapsulatedContentInfo extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectType(Identifier::SEQUENCE, $asn);
        $this->expectType(Identifier::OBJECT_IDENTIFIER, $asn->getContent()[0]);
        if (!empty($asn->getContent()[1] ?? null)) {
            $this->expectContext(0, $asn->getContent()[1]);
            $this->expectType(Identifier::OCTETSTRING, $asn->getContent()[1]->getContent()[0]);
        }
    }

    function schema(): StructuralElement
    {
        return new Sequence([
            'contentType' => Identifier::OBJECT_IDENTIFIER,
            'content'     => new Sequence\Option(new Explicit(0, Identifier::OCTETSTRING)),
        ], true);
    }

    function getContentType(): string
    {
        return $this->asn[0]->getContent();
    }

    function getContent(): ?string
    {
        $context = $asn[1] ?? null;
        if ($context) {
            return $context->getContent()[0]->getContent();
        }
        return null;
    }
}