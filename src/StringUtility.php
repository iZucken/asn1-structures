<?php


namespace izucken\asn1;


class StringUtility
{
    public static function ellipsis($string, $max, $by = "…")
    {
        if (mb_strlen($string) <= $max - mb_strlen($by)) {
            return $string;
        }
        return mb_substr($string, 0, $max - mb_strlen($by)) . $by;
    }

    public static function hexlen($hex)
    {
        return floor(strlen($hex) / 2);
    }

    public static function bitlen($hex)
    {
        return strlen(hex2bin($hex)) * 8;
    }
}