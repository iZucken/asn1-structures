<?php

namespace izucken\asn1\Structures;

use FG\ASN1\ASNObject;
use FG\ASN1\Identifier;
use izucken\asn1\Context;
use izucken\asn1\Structures\Sequence\Option;

class Sequence extends AbstractStructuralElement
{
    /**
     * @var Option|StructuralElement|string|int [] [ name => Option|StructuralElement|string|int ]
     */
    public array $sequence;
    private bool $hasTrailingOptions;

    /**
     * @param Option|StructuralElement|string|int [] $sequence [ name => Option|StructuralElement|string|int ]
     * @param false                                  $hasTrailingOptions
     */
    function __construct(array $sequence, $hasTrailingOptions = false)
    {
        $this->sequence = $sequence;
        $this->hasTrailingOptions = $hasTrailingOptions;
    }

    public function parse(ASNObject $asn, Context $ctx)
    {
        $ctx->assert($asn->getType() === Identifier::SEQUENCE, "Type {$asn->getType()}");
        $content = $asn->getContent();
        if (!$this->hasTrailingOptions) {
            $ctx->assert(count($this->sequence) <= count($content), "Trailing sequence elements not allowed");
        }
        $offset = 0;
        foreach ($this->sequence as $name => $element) {
            if ($element instanceof Option) {
                try {
                    $ctx->parse($content[$offset], $element);
                    $ctx->setAttribute($name, $ctx->evaluate());
                    // yield value
                    $offset++;
                } catch (\Exception $exception) {
                }
            } else {
                $ctx->parse($content[$offset], $element);
                $ctx->setAttribute($name, $ctx->evaluate());
                // yield value
                $offset++;
            }
        }
    }
}