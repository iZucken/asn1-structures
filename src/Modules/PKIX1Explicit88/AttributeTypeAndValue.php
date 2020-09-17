<?php


namespace izucken\asn1\Modules\PKIX1Explicit88;


use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Modules\AbstractModuleEnvelope;

class AttributeTypeAndValue extends AbstractModuleEnvelope
{
    //AttributeType  ::= OBJECT IDENTIFIER
    public function getType(): string {
        return (string)$this->asn->getContent()[0]->getContent();
    }

    //AttributeValue ::= ANY -- DEFINED BY AttributeType
    public function getValue(): string {
        return (string)$this->asn->getContent()[1]->getContent();
    }

    public function validate(ASNObject $asn)
    {
        if ($asn->getType() !== Identifier::SEQUENCE) {
            $this->error("SEQUENCE", $asn->getTypeName());
        }
        if (count($asn->getContent()) != 2) {
            $this->error("2 elements", count($asn->getContent()));
        }
        if ($asn->getContent()[0]->getType() !== Identifier::OBJECT_IDENTIFIER) {
            $this->error("first item to be OBJECT_IDENTIFIER", $asn->getContent()[0]->getTypeName());
        }
    }
}