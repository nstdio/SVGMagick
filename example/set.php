<?php
use nstdio\svg\animation\AnimateTransform;
use nstdio\svg\animation\Set;
use nstdio\svg\container\SVG;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG(500, 350);

$rect = new \nstdio\svg\shape\Rect($svg, 50, 50, 255, 125);
$rect->id = "deepPink-rectangle";
$rect->fill = "deepPink";
$rect->transform = "rotate(0) translate(0 0)";

$svg->append($rect);

$transform = new AnimateTransform($rect);
$transform->attributeName = "transform";
$transform->attributeType = "XML";
$transform->type = "rotate";
$transform->from = "0 250 150";
$transform->to = "360 250 150";
$transform->dur = "2s";
$transform->begin = "0s";
$transform->fill = "freeze";
$transform->repeatCount = "indefinite";
$transform->restart="whenNotActive";

$rect->animate($transform);

$set = new Set($svg);
$set->attributeName = "fill";
$set->to = "#0099AA";
$set->begin = "click";
$set->dur = "3s";

$rect->animate($set);

echo $svg->draw();
