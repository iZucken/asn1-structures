<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class Certificate extends AbstractModuleEnvelope
{
    public function validate(ASNObject $asn)
    {
        $this->expectEqual(Identifier::SEQUENCE, $asn->getType());
        $this->expectEqual(3, count($asn->getContent()));
        $this->expectStructure(TBSCertificate::class, $asn[0]);
        $this->expectStructure(AlgorithmIdentifier::class, $asn[1]);
        $this->expectEqual(Identifier::BITSTRING, $asn[2]->getType());
    }

    function getTbsCertificate(): TBSCertificate
    {
        return (new TBSCertificate)->setAsn($this->asn[0]);
    }

    function getSignatureAlgorithm(): AlgorithmIdentifier
    {
        return (new AlgorithmIdentifier)->setAsn($this->asn[1]);
    }

    function getSignature(): string
    {
        return $this->asn[2]->getcontent();
    }
}