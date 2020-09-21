#!/usr/bin/php
<?php

include __DIR__ . "/vendor/autoload.php";

$mode = $argv[1];
$signedFilename = $argv[2];
$columns = exec('tput cols');

$signedFile = base64_decode(file_get_contents($signedFilename));
$asn = \FG\ASN1\ASNObject::fromBinary($signedFile);
$dump = new \izucken\asn1\ObjectDump(new \izucken\asn1\OidUtility());

if ($mode === 'env') {
    $envelope = new \izucken\asn1\Modules\CryptographicMessageSyntax2004\ContentInfo;
    $context = new \izucken\asn1\Context($envelope);
    $asn = $context->evaluateStructure($asn)->getEnvelope();
}

foreach (explode("\n", $dump->treeDump($asn)) as $line) {
    echo \izucken\asn1\StringUtility::ellipsis($line, $columns) . "\n";
}

echo "\n";
