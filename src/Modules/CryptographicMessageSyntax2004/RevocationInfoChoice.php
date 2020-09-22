<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Structures\Any;
use izucken\asn1\Structures\Choice;
use izucken\asn1\Structures\Implicit;
use izucken\asn1\Structures\StructuralElement;

class RevocationInfoChoice extends AbstractModuleEnvelope
{
    public string $choice;
    public $crl;
    public $other;

    public function schema(): StructuralElement
    {
        // todo:
        return new Choice([
            "crl"   => new Any, // CertificateList
            "other" => new Implicit(1, new Any), // OtherRevocationInfoFormat
        ]);
    }
}