#!/usr/bin/php
<?php

use FG\ASN1\ASNObject;
use izucken\asn1\Context;
use izucken\asn1\Modules\CryptographicMessageSyntax2004\ContentInfo;
use izucken\asn1\ObjectDump;
use izucken\asn1\OidUtility;
use izucken\asn1\StringUtility;

include __DIR__ . "/vendor/autoload.php";

$mode = $argv[1];
$signedFilename = $argv[2];
$columns = exec('tput cols');

$signedFile = base64_decode(file_get_contents($signedFilename));
$asn = ASNObject::fromBinary($signedFile);
$dump = new ObjectDump(new OidUtility());

if ($mode === 'env') {
    $context = new Context(new ContentInfo);
    $asn = $context->evaluateStructure($asn)->getEnvelope();
}

foreach (explode("\n", $dump->treeDump($asn)) as $line) {
    echo StringUtility::ellipsis($line, $columns) . "\n";
}

echo "\n";
