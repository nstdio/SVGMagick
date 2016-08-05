<?php
use nstdio\svg\container\Defs;
use nstdio\svg\container\SVG;
use nstdio\svg\filter\Filter;
use nstdio\svg\filter\GaussianBlur;
use nstdio\svg\shape\Circle;
use nstdio\svg\text\Text;

require_once __DIR__ . '/../vendor/autoload.php';

$stDev = isset($_GET['std']) ? $_GET['std'] : 2;
$svg = new SVG(400, 300);
$svg->viewBox = "0 0 400 300";

$defs = new Defs($svg);

$gBlur = new GaussianBlur($svg);
$gBlur->stdDeviation = 2;
$filter = new Filter($defs, 'fltr', $gBlur);

$defs->append($filter);
$circle = new Circle($svg, 200, 150, 80);
$circle->fill = "#3498db";

$circle->filterGaussianBlur($stDev);
$svg->append($circle);

$text = new Text($svg, "Specify blur amount by passing \"std\" in query");
$text->x = 50;
$text->y = 30;
$svg->append($text);
echo $svg->draw();