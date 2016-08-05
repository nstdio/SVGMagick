<?php
namespace nstdio\svg\xml;

/**
 * Class NotImplementedException
 *
 * @package nstdio\svg\xml
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class NotImplementedException extends \RuntimeException
{
    public static function newInstance()
    {
        $backTrack = debug_backtrace()[1];
        return new static(
            sprintf("Method %s::%s not implemented yet, but called in %s on line %d",
                $backTrack['class'],
                $backTrack['function'],
                $backTrack['file'],
                $backTrack['line']
            )
        );
    }
}