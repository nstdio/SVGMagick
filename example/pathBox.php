<?php

use nstdio\svg\container\SVG;
use nstdio\svg\shape\Path;
use nstdio\svg\shape\Rect;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG();

$path = new Path($svg, 320, 50);
$path->apply(['fill' => 'none', 'stroke' => 'red']);
$path->vLineTo(25)->hLineTo(220)->vLineTo(78);

drawBBox($svg, $path->getBoundingBox());

(new Rect($svg, 478, 638, 1, 1))->apply(['fill' => 'none', 'stroke' => 'blue', 'stroke-width' => '1']);

echo $svg;

function drawBBox($parent, $box)
{
    (new Rect($parent, $box['height'], $box['width'], $box['x'], $box['y']))->apply([
        'fill' => 'none',
        'stroke' => 'green',
        'stroke-width' => 0.3
    ]);
}
