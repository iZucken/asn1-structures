<?php

namespace izucken\asn1\Modules\PKIX1Explicit88;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class RDNSequence extends AbstractModuleEnvelope
{
    public function validate(ASNObject $asn)
    {
        $this->expectEqual(Identifier::SEQUENCE, $asn->getType());
        foreach ($asn->getContent() as $set) {
            $this->expectStructure(RelativeDistinguishedName::class, $set);
        }
    }

    /**
     * @return RelativeDistinguishedName[]
     */
    public function getRdnSequence(): array
    {
        $rdnSequence = [];
        foreach ($this->asn->getContent() as $set) {
            $rdn = new RelativeDistinguishedName;
            $rdnSequence[] = $rdn->setAsn($set);
        }
        return $rdnSequence;
    }

    public function getOidMap()
    {
        $map = [];
        foreach ($this->getRdnSequence() as $rdn) {
            foreach ($rdn->getAttributeTypeAndValueSet() as $item) {
                $map[$item->getType()] = $item->getValue();
            }
        }
        return $map;
    }
}