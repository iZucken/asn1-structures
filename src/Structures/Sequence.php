<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Context;
use izucken\asn1\StructuralError;
use izucken\asn1\Structures\Sequence\Option;

class Sequence extends AbstractStructuralElement
{
    /**
     * @var Option[]|StructuralElement[]|string[]|int[] [ name => Option|StructuralElement|string|int ]
     */
    public array $sequence;
    private bool $hasTrailingOptions;

    /**
     * @param Option[]|StructuralElement[]|string[]|int[] $sequence [ name => Option|StructuralElement|string|int ]
     * @param false                                       $hasTrailingOptions
     */
    function __construct(array $sequence, $hasTrailingOptions = false)
    {
        $this->sequence = $sequence;
        $this->hasTrailingOptions = $hasTrailingOptions;
    }

    public function parse(ASNObject $asn, Context $ctx)
    {
        $ctx->assert($asn->getType() === Identifier::SEQUENCE, "Type " . $asn->getType());
        $content = $asn->getContent();
        if (!$this->hasTrailingOptions) {
            $sc = count($this->sequence);
            $cc = count($content);
            $ctx->assert($sc >= $cc, "Trailing sequence elements not allowed ($sc/$cc)");
        }
        $offset = 0;
        $attributes = [];
        foreach ($this->sequence as $name => $element) {
            if ($element instanceof Option) {
                if (empty($content[$offset])) {
                    $offset++;
                } else {
                    try {
                        $attributes[$name] = $ctx->parse($content[$offset], $element->getElement());
                        $offset++;
                    } catch (StructuralError $exception) {
                    }
                }
            } else {
                $ctx->assert(isset($content[$offset]), "Missing required attribute $name");
                $attributes[$name] = $ctx->parse($content[$offset], $element);
                $offset++;
            }
        }
        return $attributes;
    }
}