<?php


namespace izucken\asn1\Modules\PKIX1Explicit88;


use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class RelativeDistinguishedName extends AbstractModuleEnvelope
{
    public function validate(ASNObject $asn)
    {
        if ($asn->getType() !== Identifier::SET) {
            $this->error("SET", $asn->getTypeName());
        }
        if (count($asn->getContent()) <= 0) {
            $this->error("at least one item", "none");
        }
        foreach ($asn->getContent() as $sequence) {
            $attribute = new AttributeTypeAndValue;
            $attribute->validate($sequence);
        }
    }

    /**
     * @return AttributeTypeAndValue[]
     */
    public function getAttributeTypeAndValueSet(): array
    {
        $attributeTypeAndValueSet = [];
        foreach ($this->asn->getContent() as $item) {
            $attribute = new AttributeTypeAndValue;
            $attributeTypeAndValueSet[] = $attribute->setAsn($item);
        }
        return $attributeTypeAndValueSet;
    }
}