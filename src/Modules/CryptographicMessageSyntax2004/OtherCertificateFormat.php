<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Any;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\StructuralElement;

class OtherCertificateFormat extends AbstractModuleEnvelope
{
    public string $otherCertFormat;
    public string $otherCert; // ANY DEFINED BY otherCertFormat

    public function schema(): StructuralElement
    {
        return new Sequence([
            "otherCertFormat" => Identifier::OBJECT_IDENTIFIER,
            "otherCert"       => new Any,
        ]);
    }
}