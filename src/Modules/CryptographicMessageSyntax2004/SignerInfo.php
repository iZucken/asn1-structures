<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Modules\PKIX1Explicit88\AlgorithmIdentifier;
use izucken\asn1\Structures\Primitive;
use izucken\asn1\Structures\Sequence\Option;
use izucken\asn1\Structures\Implicit;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\SequenceOf;
use izucken\asn1\Structures\StructuralElement;
use izucken\asn1\Structures\Struct;

class SignerInfo extends AbstractModuleEnvelope
{
    public int $version;
    public SignerIdentifier $sid;
    public AlgorithmIdentifier $digestAlgorithm;
    /**
     * @var Attribute[]|void
     */
    public $signedAttrs;
    public AlgorithmIdentifier $signatureAlgorithm;
    public string $signature;
    /**
     * @var Attribute[]|void
     */
    public $unsignedAttrs;

    function schema(): StructuralElement
    {
        return new Sequence([
            'version'            => new Primitive(Identifier::INTEGER), // [0, 1, 2, 3, 4, 5]
            'sid'                => new Struct(SignerIdentifier::class),
            'digestAlgorithm'    => new Struct(AlgorithmIdentifier::class),
            'signedAttrs'        => new Option(new Implicit(0, new SequenceOf(new Struct(Attribute::class)))),
            'signatureAlgorithm' => new Struct(AlgorithmIdentifier::class),
            'signature'          => new Primitive(Identifier::OCTETSTRING),
            'unsignedAttrs'      => new Option(new Implicit(1, new SequenceOf(new Struct(Attribute::class)))),
        ], true);
    }
}