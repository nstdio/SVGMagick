<?php
namespace nstdio\svg\container;

use nstdio\svg\attributes\Transformable;
use nstdio\svg\ElementInterface;
use nstdio\svg\traits\TransformTrait;
use nstdio\svg\util\Transform;
use nstdio\svg\util\TransformInterface;

/**
 * Class G
 *
 * @property string transform
 * @package svg\container
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class G extends Container implements Transformable, TransformInterface
{
    use TransformTrait;

    public function __construct(ElementInterface $parent)
    {
        parent::__construct($parent);

        $this->transformImpl = Transform::newInstance($this->getTransformAttribute());
    }

    public function getName()
    {
        return 'g';
    }

    /**
     * @inheritdoc
     */
    public function getTransformAttribute()
    {
        return $this->transform;
    }

    /**
     * @inheritdoc
     */
    public function setTransformAttribute($transformList)
    {
        $this->transform = $transformList;
    }
}