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

    /**
     * @var TransformMatcher
     */
    private $matcher;

    private function __construct()
    {
    }

    /**
     * Use this method to instantiate Transform class.
     *
     * @param $transformString
     *
     * @return Transform
     */
    public static function newInstance($transformString = null)
    {
        $instance = new Transform();
        $instance->trans = $transformString;
        $instance->matcher = new TransformMatcher();
        $instance->sequence = $instance->matcher->makeSequence($transformString);

        foreach ($instance->sequence as $value) {
            $method = 'match' . ucfirst($value);
            $instance->data[$value] = $instance->matcher->$method($transformString);
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

    private function shortcutBuild($transform, $data)
    {
        $this->addTransformIfNeeded($transform);

        $this->setTransformData($transform, $data);

        return $this->buildTransformString();
    }
}