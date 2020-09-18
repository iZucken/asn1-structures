<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Modules\PKIX1Explicit88\AlgorithmIdentifier;
use izucken\asn1\Modules\PKIX1Explicit88\Certificate;
use izucken\asn1\Structure\AnyByLink;
use izucken\asn1\Structure\Context;
use izucken\asn1\Structure\ContextOf;
use izucken\asn1\Structure\ObjectIdentifier;
use izucken\asn1\Structure\Optional;
use izucken\asn1\Structure\Scalar;
use izucken\asn1\Structure\Sequence;
use izucken\asn1\Structure\SetOf;
use izucken\asn1\Structure\Structure;

class SignedData extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectType(Identifier::INTEGER, $asn[0]);
        $this->expectIn([0, 1, 2, 3, 4, 5], $asn[1]);
        $this->expectListOf(Identifier::SET, AlgorithmIdentifier::class, $asn[2]);
        $this->expectStructure(EncapsulatedContentInfo::class, $asn[3]);
        $offset = 3;
        if ($this->isContextTag($asn[$offset], 0)) {
            $this->expectContextOf(0, Certificate::class, $asn[$offset++]);
        }
        if ($this->isContextTag($asn[$offset], 1)) {
            $this->expectContextOf(1, RevocationInfoChoice::class, $asn[$offset++]);
        }
        $this->expectListOf(Identifier::SET, SignerInfo::class, $asn[$offset]);
    }

    function structure()
    {
        return new Sequence([
            new Scalar(Identifier::INTEGER, [0, 1, 2, 3, 4, 5]),
            new SetOf(new Structure(AlgorithmIdentifier::class)),
            new Structure(EncapsulatedContentInfo::class),
            new Optional(new ContextOf(0, new Structure(CertificateChoices::class))),
            new Optional(new ContextOf(1, new Structure(RevocationInfoChoice::class))),
            new SetOf(new Structure(SignerInfo::class)),
        ]);
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