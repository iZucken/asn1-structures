#!/usr/bin/php
<?php

include __DIR__ . "/vendor/autoload.php";

$signedFilename = $argv[1];
$signedFile = base64_decode(file_get_contents($signedFilename));
$dump = new \izucken\asn1\ObjectDump(new \izucken\asn1\OidUtility());
$expect = new \izucken\asn1\Expect;
$COLS = exec('tput cols');
$asn = \FG\ASN1\ASNObject::fromBinary($signedFile);
$envelope = new \izucken\asn1\Modules\CryptographicMessageSyntax2004\ContentInfo;
$expect->structure($asn, $envelope->structure());
$envelope->setAsn($asn);
foreach (explode("\n", $dump->treeDump($envelope)) as $line) {
    echo \izucken\asn1\StringUtility::ellipsis($line, $COLS) . "\n";
}
echo "\n";
