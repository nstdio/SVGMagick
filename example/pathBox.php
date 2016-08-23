<?php

use nstdio\svg\container\SVG;
use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Path;
use nstdio\svg\shape\Rect;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG();

$path = new Path($svg, 10, 10);
$path->apply(['fill' => 'none', 'stroke' => 'red', 'stoke-width' => 0.2]);

$path
    ->hLineTo(90, false)
    ->vLineTo(90, false)
    ->hLineTo(90, false);

(new Circle($svg, 10, 10, 3))->apply(['fill' => 'green']);
(new Circle($svg, 100, 10, 3))->apply(['fill' => 'green']);
(new Circle($svg, 100, 100, 3))->apply(['fill' => 'green']);
(new Circle($svg, 190, 100, 3))->apply(['fill' => 'green']);

drawBBox($svg, $path->getBoundingBox());

(new Rect($svg, 478, 638, 1, 1))->apply(['fill' => 'none', 'stroke' => 'blue', 'stroke-width' => '1']);

echo $svg;

function drawBBox($parent, $box)
{
    (new Rect($parent, $box['height'], $box['width'], $box['x'], $box['y']))->apply([
        'fill'         => 'none',
        'stroke'       => 'green',
        'stroke-width' => 0.3,
    ]);
}
