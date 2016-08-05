<?php
use nstdio\svg\animation\AnimateTransform;
use nstdio\svg\container\SVG;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG(500, 150);

$rect = new \nstdio\svg\shape\Rect($svg, 50, 50, 50, 50);
$rect->id = "deepPink-rectangle";
$rect->fill = "deepPink";

$svg->append($rect);

$transform = new AnimateTransform($rect);
$transform->attributeName = "transform";
$transform->attributeType = "XML";
$transform->type = "rotate";
$transform->from = "0 75 75";
$transform->to = "360 75 75";
$transform->dur = "10s";
$transform->begin = "0s; 5s; 9s; 17s;";
$transform->end="2s; 8s; 15s; 25s;";
$transform->fill="freeze";
$transform->restart="whenNotActive";

$rect->animate($transform);

echo $svg->draw();