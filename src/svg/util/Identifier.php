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

    public static function sequential(ElementInterface $root, $tag)
    {
        $count = $root->getRoot()->getElement()->getElementsByTagName($tag)->length;

        return $tag . (++$count);
    }

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