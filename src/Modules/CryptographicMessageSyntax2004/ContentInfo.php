<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class ContentInfo extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectEqual(Identifier::SEQUENCE, $asn->getType());
        $this->expectEqual(Identifier::OBJECT_IDENTIFIER, $asn[0]->getType());
        $this->expect(Identifier::isContextSpecificClass($asn[1]->getType()));
        $this->expectEqual(0, Identifier::getTagNumber($asn[1]->getType()));
        $contentType = $asn[0]->getContent();
        $content = $asn[1]->getContent()[0];
        if ($contentType === "1.2.840.113549.1.7.2") {
            $this->expectStructure(SignedData::class, $content);
        }
    }

    function getContentType(): string
    {
        return $this->asn[0]->getContent();
    }

    function content(): ASNObject
    {
        return $this->asn[1]->getContent()[0];
    }

    function getSignedData(): SignedData
    {
        return (new SignedData)->setAsn($this->content());
    }
}