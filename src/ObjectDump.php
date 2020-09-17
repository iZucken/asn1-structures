<?php


namespace izucken\asn1;


use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
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
        if ($object instanceof Certificate) {
            return "\e[33mCertificate\e[0m \e[90m{$object->getSignature()}\e[0m";
        } elseif ($object instanceof TBSCertificate) {
            return "\e[33mTBSCertificate\e[0m"
            . " v{$object->getVersion()->getVersion()}"
            . " from " . $object->getValidity()->getNotBefore()->format(DATE_ISO8601)
            . " to " . $object->getValidity()->getNotAfter()->format(DATE_ISO8601)
            . " \e[90m{$object->getSerialNumber()}\e[0m";
        } elseif ($object instanceof RDNSequence) {
            return "\e[33mDN:\e[0m " . join(", ", array_values($object->getOidMap()));
        }
        return $this->describeObject($object->getAsn());
    }

    function describeAsnObject(ASNObject $object)
    {
        $id = ord($object->getIdentifier());
        if (Identifier::isContextSpecificClass($object->getIdentifier())) {
            $tag = Identifier::getTagNumber($object->getIdentifier());
            return "\e[33m::$tag\e[0m";
        } elseif ($id === Identifier::SEQUENCE) {
            return "=>";
        } elseif ($id === Identifier::SET) {
            return "[]";
        } elseif ($id === Identifier::OCTETSTRING) {
            return "\e[33m[…" . StringUtility::hexlen($object->getContent()) . " bytes]\e[0m";
        } elseif ($id === Identifier::BITSTRING) {
            return "\e[33m[…" . StringUtility::bitlen($object->getContent()) . " bits]\e[0m";
        } elseif ($id === Identifier::NULL) {
            return "\e[90mnull\e[0m";
        } elseif ($id === Identifier::INTEGER) {
            return "\e[36m{$object->getContent()}\e[0m";
        } elseif ($id === Identifier::BOOLEAN) {
            return "\e[36m{$object->getContent()}\e[0m";
        } elseif ($id === Identifier::UTF8_STRING) {
            return "\e[36m{$object->getContent()}\e[0m";
        } elseif ($id === Identifier::PRINTABLE_STRING) {
            return "\e[36m{$object->getContent()}\e[0m";
        } elseif ($id === Identifier::NUMERIC_STRING) {
            return "\e[36m{$object->getContent()}\e[0m";
        } elseif ($id === Identifier::UTC_TIME) {
            return "\e[36m{$object}\e[0m";
        } elseif ($id === Identifier::OBJECT_IDENTIFIER) {
            $name = $this->oidUtility->name($object->getContent());
            return "\e[33m{$name}\e[0m \e[90m{$object->getContent()}\e[0m";
        } else {
            $name = Identifier::getShortName($object->getType());
            $hex = bin2hex($object->getIdentifier());
            return $object->getContent() . " \e[90m{$name} 0x{$hex}\e[0m";
        }
    }

    /**
     * @param ModuleEnvelope|ASNObject $asn
     * @param int                      $depth
     * @param string|null              $continue
     * @param string|null              $context
     */
    function treeDumpCycle($asn, $depth = 0, $continue = null, $context = null)
    {
        if ($asn instanceof ASNObject) {
            $id = ord($asn->getIdentifier());
            if ($id === Identifier::SEQUENCE) {
                $asn = Certificate::tryEnvelope($asn)
                    ?? RDNSequence::tryEnvelope($asn)
                    ?? $asn;
            }
        }
        $pad = str_repeat("  ", $depth);
        $line = ($continue ? "$continue " : $pad) . $context .  $this->describeObject($asn);
        if ($asn instanceof ModuleEnvelope) {
            if ($asn instanceof Certificate) {
                echo "$line\n";
                $this->treeDumpCycle($asn->getSignatureAlgorithm()->getAsn(), $depth + 1);
                $this->treeDumpCycle($asn->getTbsCertificate(), $depth + 1);
            } elseif ($asn instanceof TBSCertificate) {
                echo "$line\n";
                $this->treeDumpCycle($asn->getIssuer()->getRdnSequence(), $depth + 1, null, "Issuer: ");
                $this->treeDumpCycle($asn->getSubject()->getRdnSequence(), $depth + 1, null, "Subject: ");
            } elseif ($asn instanceof RDNSequence) {
                echo "$line\n";
            } else {
                $this->treeDumpCycle($asn->getAsn(), $depth);
            }
        } elseif ($id === Identifier::SEQUENCE
            || $id === Identifier::SET
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

    function treeDump($asn)
    {
        ob_start();
        $this->treeDumpCycle($asn);
        return ob_get_clean();
    }
}