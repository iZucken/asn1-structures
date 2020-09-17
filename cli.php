#!/usr/bin/php
<?php

include __DIR__ . "/vendor/autoload.php";

$signedFilename = $argv[1];
$signedFile = base64_decode(file_get_contents($signedFilename));
$dump = new \izucken\asn1\ObjectDump(new \izucken\asn1\OidUtility());
$COLS = exec('tput cols');
$asn = \FG\ASN1\ASNObject::fromBinary($signedFile);
(new \izucken\asn1\Modules\CryptographicMessageSyntax2004\ContentInfo)->validate($asn);
$envelope = (new \izucken\asn1\Modules\CryptographicMessageSyntax2004\ContentInfo)->setAsn($asn);
echo "Deemed valid\n";
foreach (explode("\n", $dump->treeDump($envelope)) as $line) {
    echo \izucken\asn1\StringUtility::ellipsis($line, $COLS) . "\n";
}
echo "\n";
