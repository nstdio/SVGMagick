<?php
use nstdio\svg\container\G;
use nstdio\svg\container\SVG;
use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Path;
use nstdio\svg\shape\Rect;
use nstdio\svg\text\Text;
use nstdio\svg\util\Bezier;

require_once __DIR__ . '/../vendor/autoload.php';

for ($i = 0; $i < 100; $i++) {
    drawQuad(mt_rand(0, 980), mt_rand(0, 480), mt_rand(0, 980), mt_rand(0, 480), mt_rand(0, 980), mt_rand(0, 480));
}

drawQuad(10, 5, 10, 150, 50, 80);

function drawQuad($mx, $my, $x1, $y1, $x, $y)
{
    $ptRadius = 6;
    $svg = new SVG('10cm', '6cm');
    $svg->getElement()->setAttribute('viewBox', '0 0 1000 600');
    $lineStyle = ['fill' => 'none', 'stroke' => '#888888', 'stroke-width' => 2];


    Rect::create($svg, 498, 998, 1, 1)->apply(['fill' => 'none', 'stroke' => 'blue', 'stroke-width' => '1']);
    Path::quadraticCurve($svg, $mx, $my, $x1, $y1, $x, $y)
        ->apply(['fill' => 'none', 'stroke' => 'red', 'stroke-width' => 1]);

    $controlsGroup = G::create($svg)->apply(['fill' => 'black']);

    Circle::create($controlsGroup, $mx, $my, $ptRadius);
    Circle::create($controlsGroup, $x, $y, $ptRadius);

    $g = G::create($svg)->apply(['fill' => '#888888']);

    Circle::create($g, $x1, $y1, $ptRadius);

    Path::line($svg, $mx, $my, $x1, $y1)
        ->lineTo($x, $y)
        ->apply($lineStyle)
        ->apply(['stroke-opacity' => 0.5]);

    $box = Bezier::quadraticBBox($mx, $my, $x1, $y1, $x, $y);

    $boxRect = Rect::createFromPointsArray($svg, $box)
        ->apply($lineStyle)
        ->apply(['stroke-dasharray' => '8 8', 'stroke-linecap' => 'round', 'fill' => 'blue', 'fill-opacity' => 0.1]);

    $coordinates = sprintf("P0(%d, %d), P1(%d, %d), P2(%d, %d). BBox(width: %.2f, height: %.2f, x: %.2f, y: %.2f)", $mx, $my, $x1, $y1, $x, $y, $boxRect->width, $boxRect->height, $boxRect->x, $boxRect->y);

    Text::create($svg, $coordinates)
        ->apply(['x' => 5, 'y' => 530, 'font-size' => '24px']);

    echo $svg;
}
