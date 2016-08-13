<?php

use nstdio\svg\container\SVG;
use nstdio\svg\shape\Path;
use nstdio\svg\shape\Rect;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG();

$path = new Path($svg, 10, 5);

$path->quadraticCurveTo(10, 150, 50, 80)->quadraticCurveTo(150, 230, 90, 60);
$path->apply(['fill' => 'none', 'stroke' => 'red']);

$box = $path->getBoundingBox();

(new Rect($svg, $box['height'], $box['width'], 10, 5))->apply(['fill' => 'none', 'stroke' => 'green']);

(new Rect($svg, 478, 638, 1, 1))->apply(['fill' => 'none', 'stroke' => 'blue', 'stroke-width' => '1']);

echo $svg;