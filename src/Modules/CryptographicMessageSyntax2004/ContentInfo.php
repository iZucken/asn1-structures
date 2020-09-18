<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structure\AnyByLink;
use izucken\asn1\Structure\Context;
use izucken\asn1\Structure\Link;
use izucken\asn1\Structure\Scalar;
use izucken\asn1\Structure\Sequence;
use izucken\asn1\Structure\Structure;

class ContentInfo extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
//        $this->expectEqual(Identifier::SEQUENCE, $asn->getType());
//        $this->expectEqual(Identifier::OBJECT_IDENTIFIER, $asn[0]->getType());
//        $this->expectContext(0, $asn[1]);
//        $contentType = $asn[0]->getContent();
//        $content = $asn[1]->getContent()[0];
//        if ($contentType === "1.2.840.113549.1.7.2") {
//            $this->expectStructure(SignedData::class, $content);
//        }
    }

    function structure()
    {
        return new Sequence([
            new Link('contentType', new Scalar(Identifier::OBJECT_IDENTIFIER, ["1.2.840.113549.1.7.2"])),
            new Context(0, [
                new AnyByLink('contentType', [
                    "1.2.840.113549.1.7.2" => new Structure(SignedData::class),
                ]),
            ]),
        ]);
    }

    function getContentType(): string
    {
        return $this->asn[0]->getContent();
    }

    function getSignedData(): SignedData
    {
        return (new SignedData)->setAsn($this->asnContent());
    }

    function asnContent(): ASNObject
    {
        return $this->asn[1]->getContent()[0];
    }
}