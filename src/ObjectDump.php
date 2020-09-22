<?php

namespace izucken\asn1;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\CryptographicMessageSyntax2004\Attribute;
use izucken\asn1\Modules\CryptographicMessageSyntax2004\CertificateChoices;
use izucken\asn1\Modules\CryptographicMessageSyntax2004\ContentInfo;
use izucken\asn1\Modules\CryptographicMessageSyntax2004\SignedData;
use izucken\asn1\Modules\CryptographicMessageSyntax2004\SignerInfo;
use izucken\asn1\Modules\ModuleEnvelope;
use izucken\asn1\Modules\PKIX1Explicit88\Certificate;
use izucken\asn1\Modules\PKIX1Explicit88\RDNSequence;
use izucken\asn1\Modules\PKIX1Explicit88\TBSCertificate;

class ObjectDump
{
    private OidUtility $oidUtility;

    function __construct(OidUtility $oidUtility)
    {
        $this->oidUtility = $oidUtility;
    }

    /**
     * @param ModuleEnvelope|ASNObject $object
     * @return string
     */
    function describeObject($object)
    {
        if ($object instanceof ModuleEnvelope) {
            return $this->describeEnvelopeObject($object);
        }
        return $this->describeAsnObject($object);
    }

    function describeEnvelopeObject(ModuleEnvelope $object)
    {
        switch (get_class($object)) {
            case CertificateChoices::class:
                return "\e[33mCertificateChoices\e[0m";
            case Certificate::class:
                return "\e[33mCertificate\e[0m \e[90m{$this->oidUtility->name($object->signatureAlgorithm->algorithm)} {$object->signature}\e[0m";
            case RDNSequence::class:
                return "\e[33mDN:\e[0m " . join(", ", array_values($object->oidMap()));
            case SignedData::class:
                return "\e[33mSignedData\e[0m v" . $object->version;
            case ContentInfo::class:
                $typeName = $this->oidUtility->name($object->contentType);
                return "\e[33mContentInfo\e[0m \e[90m$typeName {$object->contentType}\e[0m";
            case SignerInfo::class:
                return "\e[33mSignerInfo\e[0m"
                    . " v" . $object->version
                    . " \e[90m{$object->sid->issuerAndSerialNumber->serialNumber}\e[0m";
            case Attribute::class:
                return "\e[33mAttribute\e[0m"
                    . " {$this->oidUtility->name($object->type)}"
                    . " \e[90m{$object->type}\e[0m";
            case TBSCertificate::class:
                return "\e[33mTBSCertificate\e[0m"
                    . " v{$object->version}"
                    . " from " . $object->validity->notBefore->format(DATE_ISO8601)
                    . " to " . $object->validity->notAfter->format(DATE_ISO8601)
                    . " \e[90m{$object->serialNumber}\e[0m";
        }
        $classShorthand = preg_replace("#(\w+\\\\)*(\w+)#", "$2", get_class($object));
        return "$classShorthand \e[90mEnvelope\e[0m";
    }

    function describeAsnObject(ASNObject $object)
    {
        if (Identifier::isContextSpecificClass($object->getIdentifier())) {
            $tag = Identifier::getTagNumber($object->getIdentifier());
            return "\e[90m::$tag\e[0m";
        }
        switch ($object->getType()) {
            case Identifier::SET:
                return "\e[90m[]\e[0m";
            case Identifier::SEQUENCE:
                return "\e[90m=>\e[0m";
            case Identifier::OBJECT_IDENTIFIER:
                $name = $this->oidUtility->name($object->getContent());
                return "\e[33m{$name}\e[0m \e[90m{$object->getContent()}\e[0m";
            case Identifier::OCTETSTRING:
                return "\e[33m[…" . StringUtility::hexlen($object->getContent()) . " bytes]\e[0m";
            case Identifier::BITSTRING:
                return "\e[33m[…" . StringUtility::bitlen($object->getContent()) . " bits]\e[0m";
            case Identifier::NULL:
                return "\e[90mnull\e[0m";
            case Identifier::INTEGER:
            case Identifier::BOOLEAN:
            case Identifier::UTF8_STRING:
            case Identifier::PRINTABLE_STRING:
            case Identifier::NUMERIC_STRING:
            case Identifier::IA5_STRING:
            case Identifier::UTC_TIME:
                return "\e[36m{$object}\e[0m";
        }
        $name = Identifier::getShortName($object->getType());
        $hex = bin2hex($object->getIdentifier());
        return $object->getContent() . " \e[90m0x{$hex} {$name}\e[0m";
    }

    /**
     * @param ModuleEnvelope|ASNObject $asn
     * @param int                      $depth
     * @param string|null              $continue
     * @param string|null              $context
     */
    function treeDumpCycle($asn, $depth = 0, $continue = null, $context = null)
    {
        $line = ($continue ? "$continue " : str_repeat("  ", $depth)) . $context . $this->describeObject($asn);
        if ($asn instanceof ModuleEnvelope) {
            echo "$line\n";
            switch (get_class($asn)) {
                case CertificateChoices::class:
                    $this->treeDumpCycle($asn->certificate, $depth + 1);
                    break;
                case Certificate::class:
                    $this->treeDumpCycle($asn->signatureAlgorithm, $depth + 1);
                    $this->treeDumpCycle($asn->tbsCertificate, $depth + 1);
                    break;
                case ContentInfo::class:
                    $this->treeDumpCycle($asn->content, $depth + 1);
                    break;
                case SignedData::class:
                    foreach ($asn->certificates as $certificate) {
                        $this->treeDumpCycle($certificate, $depth + 1);
                    }
                    foreach ($asn->signers as $signerInfo) {
                        $this->treeDumpCycle($signerInfo, $depth + 1);
                    }
                    break;
                case SignerInfo::class:
                    foreach ($asn->signedAttrs as $attribute) {
                        $this->treeDumpCycle($attribute, $depth + 1, null);
                    }
                    break;
                case TBSCertificate::class:
                    $this->treeDumpCycle($asn->issuer->rdnSequence, $depth + 1, null, "Issuer: ");
                    $this->treeDumpCycle($asn->subject->rdnSequence, $depth + 1, null, "Subject: ");
                    break;
            }
        } elseif ($asn instanceof ASNObject) {
            if ($asn->getType() === Identifier::SEQUENCE
                || $asn->getType() === Identifier::SET
                || Identifier::isContextSpecificClass($asn->getIdentifier())) {
                if (count($asn->getContent()) === 1) {
                    $this->treeDumpCycle($asn->getContent()[0], $depth, $line);
                } else {
                    echo "$line\n";
                    foreach ($asn->getContent() as $item) {
                        $this->treeDumpCycle($item, $depth + 1);
                    }
                }
            } else {
                echo "$line\n";
            }
        }
    }

    function treeDump($asn)
    {
        ob_start();
        $this->treeDumpCycle($asn);
        return ob_get_clean();
    }
}