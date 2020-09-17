#!/usr/bin/php
<?php

use FG\ASN1\ASNObject;

include __DIR__ . "/vendor/autoload.php";

$signedFilename = $argv[1];
$signedFile = base64_decode(file_get_contents($signedFilename));

$dump = new \izucken\asn1\ObjectDump(new \izucken\asn1\OidUtility());

$COLS = exec('tput cols');
$asn = ASNObject::fromBinary($signedFile);
foreach (explode("\n", $dump->treeDump($asn)) as $line) {
    echo \izucken\asn1\StringUtility::ellipsis($line, $COLS) . "\n";
}
echo "\n";
