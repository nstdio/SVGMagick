<?php
namespace nstdio\svg\util;

use nstdio\svg\ElementInterface;

/**
 * Class Identifier
 *
 * @package nstdio\svg\util
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Identifier
{
    const FALLBACK_LENGTH = 5;

    /**
     * @param ElementInterface $root
     * @param string           $tag
     *
     * @return string
     */
    public static function sequential(ElementInterface $root, $tag)
    {

        $count = $root->getRoot()->getElement()->getElementsByTagName($tag)->length;

        return $tag . (++$count);
    }

    /**
     * Generates random string with prepended `$prefix`.
     *
     * @param string $prefix The prefix of string.
     * @param int    $length The length of string.
     *
     * @return string
     */
    public static function random($prefix, $length)
    {
        self::tryApplyFallback($length);

        $prefix .= mt_rand(1, 9);
        $length--;
        for ($i = 0; $i < $length; $i++) {
            $prefix .= mt_rand(0, 9);
        }

        return $prefix;
    }

    /**
     * To avoid duplication.
     *
     * @param $length
     *
     * @return int
     */
    private static function tryApplyFallback(&$length)
    {
        $length = intval($length);
        if (!$length || $length <= 1) {
            $length = self::FALLBACK_LENGTH;
        }
    }
}