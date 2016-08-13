<?php

use nstdio\svg\container\SVG;
use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Ellipse;
use nstdio\svg\shape\Rect;
use nstdio\svg\text\Text;

require_once __DIR__ . '/../vendor/autoload.php';


$svg = new SVG(640, 640);

$circle = (new Circle($svg, 85, 85, 80))->apply(['fill' => 'green'])
    ->diffusePointLight(); // adding diffuse light filter

(new Text($svg, 'Shape local lighting'))->apply(['x' => 20, 'y' => 2 * $circle->cx + 25]);

(new Rect($svg, 160, 160, 200, 5))->apply(['fill' => 'green'])->diffusePointLight();

(new Ellipse($svg, 500, 90, 120, 80))->apply(['fill' => 'green'])->diffusePointLight();

echo $svg;

