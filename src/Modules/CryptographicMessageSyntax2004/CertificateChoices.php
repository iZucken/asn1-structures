<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Modules\PKIX1Explicit88\Certificate;
use izucken\asn1\Modules\PKIXAttributeCertificate\AttributeCertificate as AttributeCertificateV2;
use izucken\asn1\Structures\Choice;
use izucken\asn1\Structures\Implicit;
use izucken\asn1\Structures\StructuralElement;
use izucken\asn1\Structures\Struct;

class CertificateChoices extends AbstractModuleEnvelope
{
    public ?Certificate $certificate;
    public ?AttributeCertificateV2 $v2AttrCert;
    public ?OtherCertificateFormat $other;

    function schema(): StructuralElement
    {
        return new Choice([
            "certificate" => new Struct(Certificate::class),
//            "extendedCertificate" => new Implicit(0, ExtendedCertificate::class), // Obsolete
//            "v1AttrCert" => new Implicit(1, AttributeCertificateV1::class), // Obsolete
            "v2AttrCert"  => new Implicit(2, new Struct(AttributeCertificateV2::class)),
            "other"       => new Implicit(3, new Struct(OtherCertificateFormat::class)),
        ]);
    }
}