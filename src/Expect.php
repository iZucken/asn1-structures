<?php

namespace izucken\asn1;

use FG\ASN1\ASNObject;
use izucken\asn1\Structure\StructuralElement;

class Expect
{
    const OBJECT_IDENTIFIER = 'OBJECT IDENTIFIER';

    const SET = 'SET';
    const SEQUENCE = 'SEQUENCE';

    const STRUCTURE = 'STRUCTURE';
    const ANY = 'ANY';

    const CONTEXT = 'CONTEXT';
    const TAG = 'TAG';
    const DEFINED_BY = 'DEFINED BY';

    const CHOICE = 'CHOICE';

    const OPTIONAL = 'OPTIONAL';

    const NAME = ':NAME';

    function structure(ASNObject $asn, StructuralElement $element) {

    }
}