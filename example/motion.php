<?php

use nstdio\svg\animation\AnimateMotion;
use nstdio\svg\animation\MPath;
use nstdio\svg\container\SVG;
use nstdio\svg\desc\Desc;
use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Path;
use nstdio\svg\shape\Rect;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG("5cm", "3cm");
$svg->getElement()->setAttribute('viewBox', '0 0 500 300');

$desc = new Desc($svg, 'Example animMotion01 - demonstrate motion animation computations');
$svg->append($desc);

$rect = new Rect($svg, 298, 498, 1, 1);
$rect->fill = "none";
$rect->stroke = "blue";
$rect->strokeWidth = 2;
$svg->append($rect);

$path = new Path($svg, 100, 250);
$path->id = 'path1';
$path->curveTo(100,50, 400,50, 400,250);
$path->fill = "none";
$path->stroke = "blue";
$path->strokeWidth = 7.06;

$svg->append($path);

$circle1 = new Circle($svg, 100, 250, 17.64);
$circle2 = new Circle($svg, 250, 100, 17.64);
$circle3 = new Circle($svg, 400, 250, 17.64);
$circle1->fill = "blue";
$circle2->fill = "blue";
$circle3->fill = "blue";

$svg->append($circle1, $circle2, $circle3);

$path2 = new Path($svg, -25, -12.5);
$path2->lineTo(25, -12.5)
    ->lineTo(0, -87.5)
    ->closePath(false);
$path2->fill = "yellow";
$path2->stroke = "red";
$path2->strokeWidth = 7.06;

$svg->append($path2);

$mpath = new MPath($svg, $path);

$motion = new AnimateMotion($svg, $mpath);
$motion->dur = '6s';
$motion->repeatCount = 'indefinite';
$motion->rotate = 'auto';

$path2->append($motion);

//header('Content-Encoding: gzip');
echo mb_strlen(gzencode($svg->draw(), 9));