<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class EncapsulatedContentInfo extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectEqual(Identifier::SEQUENCE, $asn->getType());
        $this->expect(count($asn->getContent()) === 2 || count($asn->getContent()) === 1);
        $this->expectEqual(Identifier::OBJECT_IDENTIFIER, $asn[0]->getType());
        $context = $asn[1] ?? null;
        if (isset($context)) {
            $this->expect(Identifier::isContextSpecificClass($context->getType()));
            $this->expectEqual(0, Identifier::getTagNumber($context->getType()));
            $this->expectEqual(Identifier::OCTETSTRING, $context->getContent()[0]->getType());
        }
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