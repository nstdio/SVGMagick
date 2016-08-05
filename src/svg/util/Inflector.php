<?php
namespace nstdio\svg\util;

/**
 * Class Inflector
 *
 * @package nstdio\svg\util
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Inflector
{
    /**
     * Converts camelCase word to camel-case.
     * @param $string string Input string.
     * @return string The converted string.
     */
    public static function camel2dash($string)
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $string));
    }
}