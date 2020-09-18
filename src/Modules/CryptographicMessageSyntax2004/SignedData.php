<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Modules\PKIX1Explicit88\Certificate;
use izucken\asn1\Modules\PKIX1Explicit88\RDNSequence;

class SignedData extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $offset = 0;
        $this->expectEqual(Identifier::SEQUENCE, $asn->getType());
        $this->expectEqual(Identifier::INTEGER, $asn[$offset++]->getType());
        $this->expectEqual(Identifier::SET, $asn[$offset++]->getType());
        $this->expectStructure(EncapsulatedContentInfo::class, $asn[$offset++]);
        if ($this->isContextTag($asn[$offset], 0)) {
            // todo: CertificateSet ::= SET OF CertificateChoices
            $this->expectContextOf(0, Certificate::class, $asn[$offset++]);
        }
        if ($this->isContextTag($asn[$offset], 1)) {
            $this->expectListOf(Identifier::SET, RevocationInfoChoice::class, $asn[$offset++]);
        }
        $this->expectListOf(Identifier::SET, SignerInfo::class, $asn[$offset++]);
    }

    function getVersion(): int
    {
        return $this->asn[0]->getContent();
    }

    /**
     * @return Certificate[]
     */
    function getCertificates(): array
    {
        if ($this->isContextTag($this->asn[3], 0)) {
            $list = [];
            foreach ($this->asn[3]->getContent() as $item) {
                $list[] = (new Certificate())->setAsn($item);
            }
            return $list;
        }
        return [];
    }

    function signerInfos(): ASNObject
    {
        $lastOffset = count($this->asn->getContent()) - 1;
        return $this->asn[$lastOffset];
    }

    /**
     * @return SignerInfo[]
     */
    function getSignerInfos(): array
    {
        $signerInfos = [];
        foreach ($this->signerInfos()->getContent() as $sequence) {
            $signerInfos[] = (new SignerInfo())->setAsn($sequence);
        }
        return $signerInfos;
    }
}