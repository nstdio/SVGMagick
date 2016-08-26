<?php
namespace nstdio\svg\traits;


use nstdio\svg\util\TransformInterface;

trait TransformTrait
{
    /**
     * @var TransformInterface
     */
    protected $transformImpl;

    /**
     * @inheritdoc
     */
    public function result()
    {
        return $this->transformImpl->result();
    }

    /**
     * @inheritdoc
     */
    public function setArgumentDelimiter($delim)
    {
        $this->transformImpl->setArgumentDelimiter($delim);
    }

    /**
     * @inheritdoc
     */
    public function rotate($angle, $cx = null, $cy = null)
    {
        $this->setTransformAttribute($this->transformImpl->rotate($angle, $cx, $cy));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function translate($x, $y = null)
    {
        $this->setTransformAttribute($this->transformImpl->translate($x, $y));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function scale($x, $y = null)
    {
        $this->setTransformAttribute($this->transformImpl->scale($x, $y));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function skewX($x)
    {
        $this->setTransformAttribute($this->transformImpl->skewX($x));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function skewY($y)
    {
        $this->setTransformAttribute($this->transformImpl->skewY($y));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function matrix(array $matrix)
    {
        $this->setTransformAttribute($this->transformImpl->matrix($matrix));

        return $this;
    }
}