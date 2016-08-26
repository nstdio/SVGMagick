<?php
namespace nstdio\svg\util;

/**
 * Class TransformMatcher
 *
 * @package nstdio\svg\util
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class TransformMatcher
{
    const ROTATE_PATTERN = "/rotate\s*\(\s*(?<a>[+-]?\d+(?:\.\d+)?)((?:\s{1,}\,?\s*|\,\s*)(?<x>[+-]?\d+(?:\.\d+)?)(?:\s{1,}\,?\s*|\,\s*)(?<y>[+-]?\d+(?:\.\d+)?))?\s*\)/";

    const TRANSLATE_PATTERN = "/translate\s*\(\s*(?<x>[+-]?\d+(?:\.\d+)?)((?:\s{1,}\,?\s*|\,\s*)(?<y>[+-]?\d+(?:\.\d+)?))?\)/";

    const SCALE_PATTERN = "/scale\s*\(\s*(?<x>[+-]?\d+(?:\.\d+)?)((?:\s{1,}\,?\s*|\,\s*)(?<y>[+-]?\d+(?:\.\d+)?))?\)/";

    const SKEWX_PATTERN = "/skewX\s*\(\s*(?<x>[+-]?\d+(?:\.\d+)?)?\)/";

    const SKEWY_PATTERN = "/skewY\s*\(\s*(?<y>[+-]?\d+(?:\.\d+)?)?\)/";

    const MATRIX_PATTERN = "/matrix\s*\(\s*((([+-]?\d+(?:\.\d+)?)(?:\s+,?\s*|,\s*)){5}([+-]?\d+(?:\.\d+)?)\s*)\)/";

    public function makeSequence($transform)
    {
        preg_match_all("/\s*(matrix|translate|scale|rotate|skew[XY])/i", $transform, $matches);

        return $matches[1];
    }

    public function matchRotate($transform)
    {
        return $this->matchPattern(self::ROTATE_PATTERN, $transform, ['a', 'x', 'y']);
    }

    public function matchSkewX($transform)
    {
        return $this->matchPattern(self::SKEWX_PATTERN, $transform, ['x']);
    }

    public function matchSkewY($transform)
    {
        return $this->matchPattern(self::SKEWY_PATTERN, $transform, ['y']);
    }

    public function matchScale($transform)
    {
        return $this->matchPattern(self::SCALE_PATTERN, $transform, ['x', 'y']);
    }


    public function matchTranslate($transform)
    {
        return $this->matchPattern(self::TRANSLATE_PATTERN, $transform, ['x', 'y']);
    }

    public function matchMatrix($transform)
    {
        preg_match(self::MATRIX_PATTERN, $transform, $matches);
        if (isset($matches[1]) === false) {
            throw new \InvalidArgumentException("Cannot match matrix transformation.");
        }

        $matrix = explode(' ', preg_replace(['/\s+/', '/\,+/'], [' ', ''], $matches[1]), 6);

        return $matrix;
    }

    private function matchPattern($pattern, $transform, $named)
    {
        preg_match($pattern, $transform, $matches);
        $ret = [];
        foreach ($named as $value) {
            $ret[] = isset($matches[$value]) ? $matches[$value] : null;
        }

        return $ret;
    }
}