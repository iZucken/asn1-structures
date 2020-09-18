<?php

namespace izucken\asn1\Structure;

class ContextOf extends AbstractStructuralElement
{
    public int $tag;
    public StructuralElement $of;

    function __construct(int $tag, StructuralElement $of)
    {
        $this->tag = $tag;
        $this->of = $of;
    }
}