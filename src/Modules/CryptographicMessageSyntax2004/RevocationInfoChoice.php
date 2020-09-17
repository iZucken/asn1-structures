<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use izucken\asn1\Modules\AbstractModuleEnvelope;

class RevocationInfoChoice extends AbstractModuleEnvelope
{
//RevocationInfoChoice ::= CHOICE
    //crl CertificateList,
    //other [1] IMPLICIT OtherRevocationInfoFormat
}