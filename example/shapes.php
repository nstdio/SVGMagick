<?php

use nstdio\svg\container\SVG;
use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Rect;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG(); // by default width = 640, height = 480.

$rect = new Rect($svg, 120, 300, 3, 3); // creating Rect object and appending <rect> element to <svg>

// You have two way to set <rect> element attributes.

// Use magic methods. Any attribute can be setted using magic setter.
// Note that for setting dash-separated attribute you must use camelCase.
// For setting stroke-linecap you must set strokeLinecap propery of corresponding object.

$rect->rx = 5;
$rect->ry = 5;
$rect->stroke = 'darkgreen';
$rect->fill = 'limegreen';
$rect->strokeWidth = 1.5;  // In this particular case strokeWidth will be converted into stroke-width.

// Or use apply method.
$rect->apply(['stroke' => 'darkgreen', 'fill' => 'limegreen', 'stroke-width' => 1.5]);
$rect->setBorderRadius(5); // setting rx and ry at once.


(new Circle($svg, 75, 200, 70))->apply([
    'fill' => '#001f3f',
    'fillOpacity' => 0.6,
    'stroke' => '#FF851B',
    'stroke-width' => 5,
]);

echo $svg; // or $svg->draw() to get svg structure;