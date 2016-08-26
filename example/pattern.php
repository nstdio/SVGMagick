<?php
use nstdio\svg\container\G;
use nstdio\svg\container\Pattern;
use nstdio\svg\container\SVG;
use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Ellipse;
use nstdio\svg\shape\Rect;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG();
$svg->apply(['style' => 'border: 1px solid blue;']);

$g = (new G($svg))->apply(['stroke' => 'green', 'stroke-width' => 0.5]);

$crossHatch = Pattern::crossHatch($svg, ['width' => 10]); // No need to explicitly create <defs>, it will be created automatically.
(new Circle($g, 52, 52, 50))->apply(['fill' => "url(#" . $crossHatch->id . ")"]);

$linesConfig = ['stroke' => 'orangered', 'stroke-width' => 0.5, 'stroke-dasharray' => '1 1'];
$crossHatch2 = Pattern::crossHatch($svg, ['width' => 20], $linesConfig); // You can customize anything you want.

//If you want to change attributes of only one line you can do the following
$secondComponentLineOfHatch = $crossHatch2->getChildAtIndex(1); // second child
$secondComponentLineOfHatch->apply(['stroke' => 'blue']);
// Or chain methods
$crossHatch2->getFirstChild()->apply(['stroke' => 'red']);

$rectangle = new Rect($g, 100, 120, 110, 2);
$rectangle->fillUrl = $crossHatch2->id;

$diagonal = Pattern::diagonalHatch($svg, ['width' => 10], $linesConfig);
$circle = new Circle($g, 295, 52, 50);
$circle->fillUrl = $diagonal->id;

$vertical = Pattern::verticalHatch($svg, ['width' => 2], ['stroke' => 'darkred']);
$ellipse = new Ellipse($g, 420, 50, 70, 30);
$ellipse->fillUrl = $vertical->id;

$patternShape = new Circle($svg, 8, 8, 8);
$patternShape->linearGradientFromBottom(['red', 'green', 'blue', 'orange', 'darkred']);
$shapePattern = Pattern::withShape($svg, $patternShape);// By passing shape object in this method it will be removed from his current location in DOM.
$circle2 = new Circle($g, 52, 160, 50);
$circle2->fillUrl = $shapePattern->id;

// Adding multiply shapes on one pattern
$rectangleConfig = ['fill' => 'black', 'fill-opacity' => 0.5, 'stroke' => 'gray', 'stroke-width' => 0.5];
$rectangle2 = new Rect($shapePattern, 7, 7, 0.5, 0.5); // Passing pattern object as parent element
$rectangle2->apply($rectangleConfig);

$rectangle3 = new Rect($shapePattern, $rectangle2->height, $rectangle2->width, 7.5, 7.5);
$rectangle3->apply($rectangleConfig);

echo $svg;
