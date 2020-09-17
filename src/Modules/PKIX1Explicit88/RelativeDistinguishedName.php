<?php


namespace izucken\asn1\Modules\PKIX1Explicit88;


use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class RelativeDistinguishedName extends AbstractModuleEnvelope
{
    public function validate(ASNObject $asn)
    {
        $this->expectEqual(Identifier::SET, $asn->getType());
        $this->expect(count($asn->getContent()) > 0, "at least one item");
        foreach ($asn->getContent() as $sequence) {
            $this->expectStructure(AttributeTypeAndValue::class, $sequence);
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