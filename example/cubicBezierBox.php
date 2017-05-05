<?php
use nstdio\svg\container\G;
use nstdio\svg\container\SVG;
use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Path;
use nstdio\svg\shape\Rect;
use nstdio\svg\text\Text;
use nstdio\svg\util\Bezier;

require_once __DIR__ . '/../vendor/autoload.php';
$p0x = 90;
$p0y = 200;

$p1x = 90;
$p1y = 380;

$p2x = 450;
$p2y = 200;

$p3x = 450;
$p3y = 380;

drawCubic(820, 239, 302, 127, 411, 237, 548, 327);
drawCubic(208, 120, 634, 421, 728, 276, 342, 69);

for ($i = 0; $i < 5; $i++) {
    drawCubic(
        mt_rand(0, 980),
        mt_rand(0, 480),
        mt_rand(0, 980),
        mt_rand(0, 480),
        mt_rand(0, 980),
        mt_rand(0, 480),
        mt_rand(0, 980),
        mt_rand(0, 480)
    );
}

function drawCubic($p0x, $p0y, $p1x, $p1y, $p2x, $p2y, $p3x, $p3y)
{
    $svg = new SVG(1000, 600);
    $svg->getElement()->removeAttribute('viewBox');

    Path::create($svg, $p0x, $p0y)
        ->lineTo($p1x, $p1y)
        ->lineTo($p2x, $p2y)
        ->lineTo($p3x, $p3y)
        ->apply(['fill' => 'none', 'stroke' => 'lightgray']);

    Path::create($svg, $p0x, $p0y)
        ->curveTo($p1x, $p1y, $p2x, $p2y, $p3x, $p3y)
        ->apply(['fill' => 'none', 'stroke' => 'red']);


    $fz = G::create($svg)->apply(['font-size' => '10px']);

    $blackGroup = G::create($fz)->apply(['fill' => 'black']);

    addCtrlPt($blackGroup, $p0x, $p0y, -20, 15, 'P0');
    addCtrlPt($blackGroup, $p1x, $p1y, -20, -10, 'P1');
    addCtrlPt($blackGroup, $p2x, $p2y, -20, -10, 'P2');
    addCtrlPt($blackGroup, $p3x, $p3y, -20, 15, 'P3');

    drawBBox($svg, $p0x, $p0y, $p1x, $p1y, $p2x, $p2y, $p3x, $p3y);

    Rect::create($svg, 498, 998, 1, 1)->apply(['fill' => 'none', 'stroke' => 'blue', 'stroke-width' => '1']);

    echo $svg;
}

function addCtrlPt($parent, $x, $y, $dx, $dy, $ptName)
{
    Circle::create($parent, $x, $y, 3);
    Text::create($parent, "$ptName($x, $y)")
        ->apply(['x' => $x + $dx, 'y' => $y + $dy]);
}

function drawBBox($parent, $p0x, $p0y, $p1x, $p1y, $p2x, $p2y, $p3x, $p3y)
{
    $box = Bezier::cubicBBox($p0x, $p0y, $p1x, $p1y, $p2x, $p2y, $p3x, $p3y);
    Rect::createFromPointsArray($parent, $box)
        ->apply([
            'stroke'           => 'gray',
            'stroke-dasharray' => '8 8',
            'stroke-linecap'   => 'round',
            'fill'             => 'blue',
            'fill-opacity'     => 0.1,
        ]);
}