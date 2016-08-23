<?php

use nstdio\svg\container\SVG;
use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Rect;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG();

$circle = (new Circle($svg, 80, 80, 50))
    ->apply(['fill' => 'none', 'stroke' => 'green']);

drawBBox($svg, $circle->getBoundingBox());

echo $svg;

function drawBBox($parent, $box)
{
    (new Rect($parent, $box['height'], $box['width'], $box['x'], $box['y']))->apply([
        'fill' => 'none',
        'stroke' => 'green',
        'stroke-width' => 0.3
    ]);
}
