<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Modules\PKIX1Explicit88\AlgorithmIdentifier;
use izucken\asn1\Structures\Sequence\Option;
use izucken\asn1\Structures\Implicit;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\SequenceOf;
use izucken\asn1\Structures\StructuralElement;

class SignerInfo extends AbstractModuleEnvelope
{
    function validate(ASNObject $asn)
    {
        $this->expectType(Identifier::SEQUENCE, $asn);
        $offset = 0;
        $this->expectType(Identifier::INTEGER, $asn[$offset++]);
        $this->expectStructure(SignerIdentifier::class, $asn[$offset++]);
        $this->expectStructure(AlgorithmIdentifier::class, $asn[$offset++]);
        if ($this->isContextTag($asn[$offset], 0)) {
            $this->expectStructure(Attributes::class, $asn[$offset++]);
        }
        $this->expectStructure(AlgorithmIdentifier::class, $asn[$offset++]);
        $this->expectType(Identifier::OCTETSTRING, $asn[$offset++]);
        if (isset($asn[$offset]) && $this->isContextTag($asn[$offset], 0)) {
            $this->expectStructure(Attributes::class, $asn[$offset++]);
        }
    }

    function schema(): StructuralElement
    {
        return new Sequence([
            'version'            => Identifier::INTEGER, // [0, 1, 2, 3, 4, 5]
            'sid'                => SignerIdentifier::class,
            'digestAlgorithm'    => AlgorithmIdentifier::class,
            'signedAttrs'        => new Option(new Implicit(0, new SequenceOf(Attributes::class))),
            'signatureAlgorithm' => AlgorithmIdentifier::class,
            'signature'          => Identifier::OCTETSTRING,
            'unsignedAttrs'      => new Option(new Implicit(1, new SequenceOf(Attributes::class))),
        ], true);
    }

    function getVersion(): int
    {
        return $this->asn[0]->getContent();
    }

    function getSid(): SignerIdentifier
    {
        return (new SignerIdentifier)->setAsn($this->asn[1]);
    }

    function getSignedAttributes(): ?Attributes
    {
        if ($this->isContextTag($this->asn[3], 0)) {
            return (new Attributes)->setAsn($this->asn[3]);
        }
        return null;
    }
}