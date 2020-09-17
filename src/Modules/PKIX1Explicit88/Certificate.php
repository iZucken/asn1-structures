<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;

class Certificate {
//    Certificate  ::=  SEQUENCE
    function tbsCertificate(): TBSCertificate {

    }
    function signatureAlgorithm(): AlgorithmIdentifier {

    }
    function signature(): string {
        // BIT STRING
    }

    function construct(ASNObject $asn)
    {
        if ($asn->getType() !== Identifier::SEQUENCE) {
            return null;
        }
    }
}