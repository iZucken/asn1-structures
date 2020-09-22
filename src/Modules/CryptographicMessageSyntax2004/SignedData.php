<?php

namespace izucken\asn1\Modules\CryptographicMessageSyntax2004;

use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;
use izucken\asn1\Modules\PKIX1Explicit88\AlgorithmIdentifier;
use izucken\asn1\Structures\Implicit;
use izucken\asn1\Structures\Primitive;
use izucken\asn1\Structures\Sequence;
use izucken\asn1\Structures\SequenceOf;
use izucken\asn1\Structures\SetOf;
use izucken\asn1\Structures\StructuralElement;
use izucken\asn1\Structures\Struct;

class SignedData extends AbstractModuleEnvelope
{
    public int $version;
    /**
     * @var AlgorithmIdentifier[]
     */
    public array $digest;
    public EncapsulatedContentInfo $encapsulated;
    /**
     * @var CertificateChoices[]|void
     */
    public $certificates;
    /**
     * @var RevocationInfoChoice[]|void
     */
    public $revocations;
    /**
     * @var SignerInfo[]
     */
    public array $signers;

    function schema(): StructuralElement
    {
        return new Sequence([
            'version'      => new Primitive(Identifier::INTEGER), // [0, 1, 2, 3, 4, 5]
            'digest'       => new SetOf(new Struct(AlgorithmIdentifier::class)),
            'encapsulated' => new Struct(EncapsulatedContentInfo::class),
            'certificates' => new Sequence\Option(new Implicit(0, new SequenceOf(new Struct(CertificateChoices::class)))),
            'revocations'  => new Sequence\Option(new Implicit(1, new SequenceOf(new Struct(RevocationInfoChoice::class)))),
            'signers'      => new SetOf(new Struct(SignerInfo::class)),
        ]);
    }
}