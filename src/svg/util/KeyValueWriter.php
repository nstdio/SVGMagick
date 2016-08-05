<?php
namespace nstdio\svg\util;

use nstdio\svg\XMLDocumentInterface;

/**
 * Class KeyValueWriter
 *
 * @package nstdio\svg\util
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class KeyValueWriter
{
    public static function apply(XMLDocumentInterface $element, array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $element->setAttribute($key, $value);
        }
    }

    public static function styleArrayToString(array $input)
    {
        return self::array2String($input, ':', ';');
    }

    public static function array2String(array $input, $keyValueDelimiter, $valueEndDelimiter)
    {
        $ret = '';
        foreach ($input as $key => $value) {
            if (is_string($key) && !is_array($value) && $key) {
                $ret .= $key . $keyValueDelimiter . $value . $valueEndDelimiter;
            }
            if (is_array($value)) {
                $ret .= self::array2String($value, $keyValueDelimiter, $valueEndDelimiter);
            }
        }

        return $ret;
    }
}