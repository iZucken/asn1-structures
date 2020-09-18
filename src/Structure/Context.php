<?php

namespace izucken\asn1\Structure;

class Context extends AbstractStructuralElement
{
    public int $tag;
    public array $content;

    function __construct(int $tag, array $content)
    {
        $this->tag = $tag;
        $this->content = $content;
    }
}