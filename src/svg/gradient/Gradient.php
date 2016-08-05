<?php
namespace nstdio\svg\gradient;

use nstdio\svg\container\ContainerInterface;
use nstdio\svg\ElementInterface;
use nstdio\svg\SVGElement;
use nstdio\svg\traits\ElementTrait;
use nstdio\svg\util\Identifier;

/**
 * Class Gradient
 *
 * @package nstdio\svg\gradient
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class Gradient extends SVGElement implements ContainerInterface
{
    use ElementTrait;

    const LINEAR = 'linear';

    const RADIAL = 'radial';

    public function __construct(ElementInterface $svg, $id = null)
    {
        parent::__construct($svg);

        $this->id = $id === null ? Identifier::random('__gradient', 4) : $id;
    }

    /**
     * @param Stop|Stop[] $stops
     */
    public function appendStop(Stop $stops)
    {
        $stops = func_get_args();
        foreach ($stops as $stop) {
            $this->append($stop);
        }
    }
}