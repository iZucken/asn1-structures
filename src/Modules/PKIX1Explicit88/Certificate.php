<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class Certificate extends AbstractModuleEnvelope
{
    public function validate(ASNObject $asn)
    {
        $this->expect(Identifier::SEQUENCE, $asn->getType());
        $this->expect(3, count($asn->getContent()));
        $this->expectStructure(TBSCertificate::class, $asn->getContent()[0]);
        $this->expectStructure(AlgorithmIdentifier::class, $asn->getContent()[1]);
        $this->expect(Identifier::BITSTRING, $asn->getContent()[2]->getType());
    }

    function getTbsCertificate(): TBSCertificate
    {
        return (new TBSCertificate)->setAsn($this->asn->getContent()[0]);
    }

    function getSignatureAlgorithm(): AlgorithmIdentifier
    {
        return (new AlgorithmIdentifier)->setAsn($this->asn->getContent()[1]);
    }

    function getSignature(): string
    {
        return $this->asn->getContent()[2]->getcontent();
    }
}