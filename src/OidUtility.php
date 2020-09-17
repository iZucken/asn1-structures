<?php

namespace izucken\asn1;

use FG\ASN1\OID;

class OidUtility
{
    const LIST = [
        // This OID is defined in Annex A of Rec. ITU-T X.520 | ISO/IEC 9594-6 "The Directory: Selected attribute types". See also IETF RFC 4519.
        "2.5.4.42"                          => "givenName(42)",
        "1.2.840.113549.1.7.2"              => "Signed data",
        "1.2.643.100.1"                     => "OGRN",
        "1.2.643.3.131.1.1"                 => "INN",
        // Defined in Internet-Draft draft-deremin-rfc4491-bis-04.
        "1.2.643.100.3"                     => "SNILS",
        "1.3.6.1.4.1.311.21.7"              => "szOID_CERTIFICATE_TEMPLATE",
        "1.3.6.1.4.1.311.21.10"             => "szOID_APPLICATION_CERT_POLICIES",
        // No check extension Defined in IETF RFC 2560.
        "1.3.6.1.5.5.7.48.1.5"              => "no-check(5)",
        // Средство электронной подписи владельца
        "1.2.643.100.112"                   => "Средства ЭП владельца",
        // Средства электронной подписи и УЦ издателя
        "1.2.643.100.111"                   => "Средства ЭП УЦ издателя",
        // Signing certificate V2 | id-aa-signingCertificateV2(47) | See IETF RFC 5126
        "1.2.840.113549.1.9.16.2.47"        => "Signing certificate V2",
        // GOST R 3410-2001 CryptoPro-XchA
        "1.2.643.2.2.36.0"                  => "cryptopro-XchA(0)",
        // GOST R 34.11-94 (Russian hash algorithm)
        "1.2.643.2.2.9"                     => "gostR3411-94(9)",
        // GOST R 34.10-2012 signature algorithm with 256-bit key length and GOST R 34.11-2012 hash function with 256-bit hash code
        "1.2.643.7.1.1.3.2"                 => "gost3410-12-256(2)",
        // GOST R 34.10-2012 public keys with 256 bits private key length
        "1.2.643.7.1.1.1.1"                 => "gost3410-12-256(1)",
        // GOST R 34.11-2012 hash function with 256-bit hash code
        "1.2.643.7.1.1.2.2"                 => "gost3411-12-256(2)",
        // Automatically extracted from IETF RFC 5652.
        "1.2.840.113549.1.7.1"              => "Arbitrary data",
        // GOST R 3410-2001-CryptoPro-A
        "1.2.643.2.2.35.1"                  => "cryptopro-A(1)",
        // See Object IDs associated with Microsoft cryptography.
        "1.3.6.1.4.1.311.20.2"              => "szOID_ENROLL_CERTTYPE_EXTENSION",
        // See Object IDs associated with Microsoft cryptography.
        "1.3.6.1.4.1.311.21.1"              => "Certificate services Certification Authority (CA) version",
        // встречается как "значение" оида (строка)
        "1.2.643.3.61.1.1.6.502710.3.4.2.1" => "Руководитель",
    ];

    function name($oid)
    {
        $oidName = OID::getName($oid, false);
        if ($oidName == $oid) {
            $oidName = self::LIST[$oid] ?? $oid;
        }
        return $oidName;
    }
}