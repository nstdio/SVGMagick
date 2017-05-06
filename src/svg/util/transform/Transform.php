<?php
namespace nstdio\svg\util\transform;

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

    private $argDelimiter = TransformInterface::TRANSFORM_ARG_DELIM_SPACE;

    /**
     * @var PackerInterface
     */
    private $packer;

    private function __construct()
    {
    }

    /**
     * Use this method to instantiate Transform class.
     *
     * @param null|string                    $transformString
     *
     * @param null|TransformMatcherInterface $matcher
     *
     * @param PackerInterface                $packer
     *
     * @return Transform
     */
    public static function newInstance($transformString = null, TransformMatcherInterface $matcher = null,
                                       PackerInterface $packer = null)
    {
        $instance = new Transform();
        $matcherImpl = $matcher === null ? new TransformMatcher() : $matcher;
        $instance->packer = $packer === null ? new Packer() : $packer;
        $instance->trans = $transformString === null ? '' : $transformString;
        $instance->sequence = $matcherImpl->makeSequence($transformString);

        foreach ($instance->sequence as $value) {
            $method = 'match' . ucfirst($value);
            $instance->data[][$value] = $matcherImpl->$method($transformString);
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
        if ($delim !== TransformInterface::TRANSFORM_ARG_DELIM_COMMA &&
            $delim !== TransformInterface::TRANSFORM_ARG_DELIM_COMMA_SPACE &&
            $delim !== TransformInterface::TRANSFORM_ARG_DELIM_SPACE
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

        return $this->shortcutBuild('rotate', [floatval($angle), $cx, $cy]);
    }

    private function shortcutBuild($transform, $data)
    {
        $this->sequence[] = $transform;

        foreach ($data as $key => $item) {
            if ($item !== null && !is_float($item) && !is_int($item)) {
                $data[$key] = floatval($item);
            }
        }
        $this->data[][$transform] = $data;

        return $this->buildTransformString();
    }

    private function buildTransformString()
    {
        $ret = '';
        foreach ($this->data as $key => $data) {
            $transform = array_keys($data)[0];

            $ret .= $transform . "(" . rtrim(implode($this->argDelimiter, $data[$transform])) . ") ";
        }

        $this->trans = rtrim($ret);

        return $this->trans;
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
        return $this->shortcutBuild('scale', [floatval($x), $y]);
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
            throw new \InvalidArgumentException("Invalid matrix size. You must provide en array with 6 elements. " . count($matrix) . " elements given.");
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

    /**
     * @inheritdoc
     */
    public function compact()
    {
        $this->data = $this->packer->pack($this->data);

        return $this->buildTransformString();
    }

    /**
     * Converts all transformations to matrix.
     *
     * @return string The converted to matrix string transformation.
     */
    public function toMatrix()
    {
        $this->data = $this->packer->toMatrix($this->data);

        return $this->buildTransformString();
    }
}