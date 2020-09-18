<?php

namespace izucken\asn1;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\CryptographicMessageSyntax2004\Attribute;
use izucken\asn1\Modules\CryptographicMessageSyntax2004\ContentInfo;
use izucken\asn1\Modules\CryptographicMessageSyntax2004\SignedAttributes;
use izucken\asn1\Modules\CryptographicMessageSyntax2004\SignedData;
use izucken\asn1\Modules\CryptographicMessageSyntax2004\SignerIdentifier;
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
        if ($object instanceof Certificate) {
            return "\e[33mCertificate\e[0m \e[90m{$object->getSignature()}\e[0m";
        } elseif ($object instanceof ContentInfo) {
            $typeName = $this->oidUtility->name($object->getContentType());
            return "\e[33mContentInfo\e[0m \e[90m$typeName {$object->getContentType()}\e[0m";
        } elseif ($object instanceof SignedData) {
            return "\e[33mSignedData\e[0m"
                . " v" . $object->getVersion();
        } elseif ($object instanceof SignerInfo) {
            $sidVisual = $object->getSid()->getIssuerAndSerialNumber()->getSerialNumber();
            return "\e[33mSignerInfo\e[0m"
                . " v" . $object->getVersion()
                . " \e[90m$sidVisual\e[0m";
        } elseif ($object instanceof Attribute) {
            return "\e[33mAttribute\e[0m"
                . " {$this->oidUtility->name($object->getType())}"
                . " \e[90m{$object->getType()}\e[0m";
        } elseif ($object instanceof TBSCertificate) {
            return "\e[33mTBSCertificate\e[0m"
                . " v{$object->getVersion()->getVersion()}"
                . " from " . $object->getValidity()->getNotBefore()->format(DATE_ISO8601)
                . " to " . $object->getValidity()->getNotAfter()->format(DATE_ISO8601)
                . " \e[90m{$object->getSerialNumber()}\e[0m";
        } elseif ($object instanceof RDNSequence) {
            return "\e[33mDN:\e[0m " . join(", ", array_values($object->getOidMap()));
        }
        $classShorthand = preg_replace("#(\w+\\\\)*(\w+)#","$2",get_class($object));
        return "$classShorthand \e[90mEnvelope\e[0m";
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
        $pad = str_repeat("  ", $depth);
        $line = ($continue ? "$continue " : $pad) . $context . $this->describeObject($asn);
        if ($asn instanceof ModuleEnvelope) {
            echo "$line\n";
            if ($asn instanceof Certificate) {
                $this->treeDumpCycle($asn->getSignatureAlgorithm()->getAsn(), $depth + 1);
                $this->treeDumpCycle($asn->getTbsCertificate(), $depth + 1);
            } elseif ($asn instanceof ContentInfo) {
                $this->treeDumpCycle($asn->getSignedData(), $depth + 1);
            } elseif ($asn instanceof SignedData) {
                foreach ($asn->getCertificates() as $certificate) {
                    $this->treeDumpCycle($certificate, $depth + 1);
                }
                foreach ($asn->getSignerInfos() as $signerInfo) {
                    $this->treeDumpCycle($signerInfo, $depth + 1);
                }
            } elseif ($asn instanceof SignerInfo) {
                $sa = $asn->getSignedAttributes();
                if ($sa) {
                    foreach ($sa->getAttributes() as $attribute) {
                        $this->treeDumpCycle($attribute, $depth + 1, null, "Signed ");
                    }
                }
            } elseif ($asn instanceof Attribute) {
                foreach ($asn->getValues() as $value) {
                    $this->treeDumpCycle($value, $depth + 1);
                }
            } elseif ($asn instanceof TBSCertificate) {
                $this->treeDumpCycle($asn->getIssuer()->getRdnSequence(), $depth + 1, null, "Issuer: ");
                $this->treeDumpCycle($asn->getSubject()->getRdnSequence(), $depth + 1, null, "Subject: ");
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