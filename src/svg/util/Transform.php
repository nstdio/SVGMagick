<?php
namespace nstdio\svg\util;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

/**
 * Class Transform
 *
 * @package nstdio\svg\util
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
final class Transform implements TransformInterface
{
    private $trans;

    private $data;

    private $sequence;

    private $argDelimiter = TransformInterface::ARG_DELIM_SPACE;

    const ROTATE_PATTERN = "/rotate\s*\(\s*(?<a>[+-]?\d+(?:\.\d+)?)((?:\s{1,}\,?\s*|\,\s*)(?<x>[+-]?\d+(?:\.\d+)?)(?:\s{1,}\,?\s*|\,\s*)(?<y>[+-]?\d+(?:\.\d+)?))?\s*\)/";

    const TRANSLATE_PATTERN = "/translate\s*\(\s*(?<x>[+-]?\d+(?:\.\d+)?)((?:\s{1,}\,?\s*|\,\s*)(?<y>[+-]?\d+(?:\.\d+)?))?\)/";

    const SCALE_PATTERN = "/scale\s*\(\s*(?<x>[+-]?\d+(?:\.\d+)?)((?:\s{1,}\,?\s*|\,\s*)(?<y>[+-]?\d+(?:\.\d+)?))?\)/";

    const SKEW_PATTERN = "/skew([XY])\s*\(\s*(?<x>[+-]?\d+(?:\.\d+)?)?\)/";

    const MATRIX_PATTERN = "/matrix\s*\(\s*((([+-]?\d+(?:\.\d+)?)(?:\s+,?\s*|,\s*)){5}([+-]?\d+(?:\.\d+)?)\s*)\)/";

    private function __construct()
    {
    }

    /**
     * Use this method to instantiate Transform class.
     *
     * @param $transformString
     *
     * @uses matchRotate()
     * @uses matchSkewX()
     * @uses matchSkewY()
     * @uses matchTranslate()
     * @uses matchScale()
     * @uses matchMatrix()
     *
     * @return Transform
     */
    public static function newInstance($transformString = null)
    {
        $instance = new Transform();
        $instance->trans = $transformString;
        $instance->sequence = $instance->makeSequence();

        foreach ($instance->sequence as $value) {
            $method = 'match' . ucfirst($value);
            $instance->data[$value] = $instance->$method();
        }

        return $instance;
    }

    /**
     * @inheritdoc
     */
    public function result()
    {
        return $this->trans;
    }

    /**
     * @inheritdoc
     */
    public function setArgumentDelimiter($delim)
    {
        if ($delim !== TransformInterface::ARG_DELIM_COMMA &&
            $delim !== TransformInterface::ARG_DELIM_COMMA_SPACE &&
            $delim !== TransformInterface::ARG_DELIM_SPACE
        ) {
            throw new \InvalidArgumentException("Invalid delimiter. See TransformInterface::setArgumentDelimiter documentation for valid value.");
        }

        $this->argDelimiter = $delim;
    }

    /**
     * @inheritdoc
     */
    public function rotate($angle, $cx = null, $cy = null)
    {
        if ($cx !== null && $cy === null) {
            $cy = $cx;
        }
        if ($cy !== null && $cx === null) {
            $cx = $cy;
        }

        return $this->shortcutBuild('rotate', [$angle, $cx, $cy]);
    }

    /**
     * @inheritdoc
     */
    public function translate($x, $y = null)
    {
        return $this->shortcutBuild('translate', [$x, $y]);
    }

    /**
     * @inheritdoc
     */
    public function scale($x, $y = null)
    {
        return $this->shortcutBuild('scale', [$x, $y]);
    }

    /**
     * @inheritdoc
     */
    public function skewX($x)
    {
        return $this->shortcutBuild('skewX', [$x]);
    }

    /**
     * @inheritdoc
     */
    public function skewY($y)
    {
        return $this->shortcutBuild('skewY', [$y]);
    }

    /**
     * @inheritdoc
     */
    public function matrix(array $matrix)
    {
        if (count($matrix) !== 6) {
            throw new InvalidArgumentException("Invalid matrix size. You must provide en array with 6 elements. " . count($matrix) . " elements given.");
        }

        return $this->shortcutBuild('matrix', $matrix);
    }

    /**
     * @return mixed
     */
    private function matchRotate()
    {
        return $this->matchPattern(self::ROTATE_PATTERN, ['a', 'x', 'y']);
    }

    private function matchSkewX()
    {
        return $this->matchSkew();
    }

    private function matchSkewY()
    {
        return $this->matchSkew();
    }

    private function matchSkew()
    {
        return $this->matchPattern(self::SKEW_PATTERN, ['x']);
    }

    private function matchScale()
    {
        return $this->matchPattern(self::SCALE_PATTERN, ['x', 'y']);
    }

    private function matchMatrix()
    {
        preg_match(self::MATRIX_PATTERN, $this->trans, $matches);
        if (isset($matches[1]) === false) {
            throw new \InvalidArgumentException("Cannot match matrix transformation.");
        }

        $matrix = explode(' ', preg_replace(['/\s+/', '/\,+/'], [' ', ''], $matches[1]), 6);

        return $matrix;
    }

    private function matchTranslate()
    {
        return $this->matchPattern(self::TRANSLATE_PATTERN, ['x', 'y']);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    private function hasTransform($transform)
    {
        return in_array($transform, $this->sequence);
    }

    private function getTransform($transform)
    {
        if ($this->hasTransform($transform)) {
            return $this->data[$transform];
        }

        return null;
    }

    private function makeSequence()
    {
        preg_match_all("/\s*(matrix|translate|scale|rotate|skew[XY])/i", $this->trans, $matches);

        return $matches[1];
    }

    private function addTransformSequence($transform)
    {
        $this->sequence[] = $transform;
    }

    private function buildTransformString()
    {
        $ret = '';
        foreach ($this->sequence as $transform) {
            $ret .= $transform . "(" . rtrim(implode($this->argDelimiter, $this->data[$transform])) . ") ";
        }
        $this->trans = rtrim($ret);

        return $this->trans;
    }

    private function setTransformData($transform, $data)
    {
        if (isset($this->data[$transform]) === true) {
            $oldData = $this->data[$transform];
            foreach ($data as $key => $item) {
                if ($item === null) {
                    $data[$key] = $oldData[$key];
                }
            }
        }
        $this->data[$transform] = $data;
    }

    private function addTransformIfNeeded($transform)
    {
        if ($this->getTransform($transform) === null) {
            $this->addTransformSequence($transform);
        }
    }

    private function matchPattern($pattern, $named)
    {
        preg_match($pattern, $this->trans, $matches);
        $ret = [];
        foreach ($named as $value) {
            $ret[] = isset($matches[$value]) ? $matches[$value] : null;
        }

        return $ret;
    }

    private function shortcutBuild($transform, $data)
    {
        $this->addTransformIfNeeded($transform);

        $this->setTransformData($transform, $data);

        return $this->buildTransformString();
    }
}