#!/usr/bin/php
<?php

include __DIR__ . "/vendor/autoload.php";

$signedFile = base64_decode(file_get_contents($argv[1]));
$asn = \FG\ASN1\ASNObject::fromBinary($signedFile);
$dump = new \izucken\asn1\ObjectDump(new \izucken\asn1\OidUtility());
$context = new \izucken\asn1\Context();

if ('env' === $argv[2] ?? null) {
    $context = new \izucken\asn1\Context();
    $asn = $context->parse($asn, new \izucken\asn1\Structures\Struct(
        \izucken\asn1\Modules\CryptographicMessageSyntax2004\ContentInfo::class
    ));
}

$columns = exec('tput cols');
foreach (explode("\n", $dump->treeDump($asn)) as $line) {
    echo \izucken\asn1\StringUtility::ellipsis($line, $columns) . "\n";
}
echo "\n";