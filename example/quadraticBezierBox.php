<?php
use nstdio\svg\container\G;
use nstdio\svg\container\SVG;
use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Path;
use nstdio\svg\shape\Rect;
use nstdio\svg\text\Text;
use nstdio\svg\util\Bezier;

require_once __DIR__ . '/../vendor/autoload.php';

for ($i = 0; $i < 2; $i++) {
    drawQuad(mt_rand(0, 980), mt_rand(0, 480), mt_rand(0, 980), mt_rand(0, 480), mt_rand(0, 980), mt_rand(0, 480));
}

drawQuad(10, 5, 10, 150, 50, 80);

function drawQuad($mx, $my, $x1, $y1, $x, $y)
{
    $ptRadius = 6;
    $svg = new SVG('10cm', '6cm');
    $svg->getElement()->setAttribute('viewBox', '0 0 1000 600');
    $lineStyle = ['fill' => 'none', 'stroke' => '#888888', 'stroke-width' => 2];


    (new Rect($svg, 498, 998, 1, 1))->apply(['fill' => 'none', 'stroke' => 'blue', 'stroke-width' => '1']);
    (new Path($svg, $mx, $my))
        ->quadraticCurveTo($x1, $y1, $x, $y)
        ->apply(['fill' => 'none', 'stroke' => 'red', 'stroke-width' => 1]);

    $controlsGroup = new G($svg);
    $controlsGroup->fill = 'black';

    (new Circle($controlsGroup, $mx, $my, $ptRadius));
    (new Circle($controlsGroup, $x, $y, $ptRadius));


    $g = (new G($svg))->apply(['fill' => '#888888']);

    (new Circle($g, $x1, $y1, $ptRadius));

    $path2 = new Path($svg, $mx, $my);
    $path2->lineTo($x1, $y1)
        ->lineTo($x, $y)
        ->apply($lineStyle)->apply(['stroke-opacity' => 0.5]);

    $box = Bezier::quadraticBBox($mx, $my, $x1, $y1, $x, $y);

    $boxRect = (new Rect($svg, $box['height'], $box['width'], $box['x'], $box['y']))->apply($lineStyle)
        ->apply(['stroke-dasharray' => '8 8', 'stroke-linecap' => 'round', 'fill' => 'blue', 'fill-opacity' => 0.1]);

    $coordinates = sprintf("P0(%d, %d), P1(%d, %d), P2(%d, %d). BBox(width: %.2f, height: %.2f, x: %.2f, y: %.2f)", $mx, $my, $x1, $y1, $x, $y, $boxRect->width, $boxRect->height, $boxRect->x, $boxRect->y);
    (new Text($svg, $coordinates))->apply(['x' => 5, 'y' => 530, 'font-size' => '24px']);

    echo $svg;
}
