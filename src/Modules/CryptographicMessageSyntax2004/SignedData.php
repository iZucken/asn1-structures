<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Modules\PKIX1Explicit88\AlgorithmIdentifier;
use izucken\asn1\Modules\PKIX1Explicit88\Certificate;
use izucken\asn1\Structures\Implicit;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\SequenceOf;
use izucken\asn1\Structures\SetOf;
use izucken\asn1\Structures\StructuralElement;

class SignedData extends AbstractModuleEnvelope
{
    public $version;
    public $digest;
    public $encapsulated;
    public $certificates;
    public $revocations;
    public $signers;

    function validate(ASNObject $asn)
    {
        $this->expectType(Identifier::INTEGER, $asn[0]);
        $this->expectListOf(Identifier::SET, AlgorithmIdentifier::class, $asn[1]);
        $this->expectStructure(EncapsulatedContentInfo::class, $asn[2]);
        $offset = 3;
        if ($this->isContextTag($asn[$offset], 0)) {
            $this->expectContextOf(0, Certificate::class, $asn[$offset++]);
        }
        if ($this->isContextTag($asn[$offset], 1)) {
            $this->expectContextOf(1, RevocationInfoChoice::class, $asn[$offset++]);
        }
        $this->expectListOf(Identifier::SET, SignerInfo::class, $asn[$offset]);
    }

    function schema(): StructuralElement
    {
        return new Sequence([
            'version'      => Identifier::INTEGER, // [0, 1, 2, 3, 4, 5]
            'digest'       => new SetOf(AlgorithmIdentifier::class),
            'encapsulated' => EncapsulatedContentInfo::class,
            'certificates' => new Sequence\Option(new Implicit(0, new SequenceOf(CertificateChoices::class))),
            'revocations'  => new Sequence\Option(new Implicit(1, new SequenceOf(RevocationInfoChoice::class))),
            'signers'      => new SetOf(SignerInfo::class),
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