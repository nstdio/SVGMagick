<?php
namespace nstdio\svg\shape;


use nstdio\svg\Animatable;
use nstdio\svg\attributes\Styleable;
use nstdio\svg\attributes\Transformable;
use nstdio\svg\Filterable;
use nstdio\svg\GradientInterface;
use nstdio\svg\util\transform\TransformInterface;

interface ShapeInterface extends Styleable, Animatable, Filterable, GradientInterface, TransformInterface, Transformable
{

}