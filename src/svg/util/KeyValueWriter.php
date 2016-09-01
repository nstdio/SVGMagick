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

    /**
     * @param \DOMNode $element
     * @param array    $except
     *
     * @return array
     */
    public static function allAttributes(\DOMNode $element, array $except = [])
    {
        $attributes = [];
        $length = $element->attributes->length;
        for ($i = 0; $i < $length; $i++) {
            $node = $element->attributes->item($i);
            if (!in_array($node->nodeName, $except)) {
                $attributes[$node->nodeName] = $node->nodeValue;
            }
        }

        return $attributes;
    }

    public static function styleArrayToString(array $input)
    {
        return self::array2String($input, ':', ';');
    }

    /**
     * @param array  $input
     * @param string $keyValueDelimiter
     * @param string $valueEndDelimiter
     *
     * @return string
     */
    public static function array2String(array $input, $keyValueDelimiter, $valueEndDelimiter)
    {
        $ret = '';
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $ret .= self::array2String($value, $keyValueDelimiter, $valueEndDelimiter);
            } elseif ($key) {
                $ret .= $key . $keyValueDelimiter . $value . $valueEndDelimiter;

            }
        }

        return $ret;
    }
}