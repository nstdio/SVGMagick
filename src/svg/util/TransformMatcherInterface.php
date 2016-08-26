<?php
namespace nstdio\svg\util;

/**
 * Interface TransformMatcherInterface
 *
 * @package nstdio\svg\util
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface TransformMatcherInterface
{
    public function makeSequence($transform);

    public function matchRotate($transform);

    public function matchSkewX($transform);

    public function matchSkewY($transform);

    public function matchScale($transform);

    public function matchTranslate($transform);

    public function matchMatrix($transform);
}