<?php
use nstdio\svg\container\Defs;
use nstdio\svg\container\G;
use nstdio\svg\container\SVG;
use nstdio\svg\gradient\UniformGradient;
use nstdio\svg\shape\Rect;
use nstdio\svg\text\Text;

require_once __DIR__ . '/../vendor/autoload.php';

$style = ['stroke' => 'lightgray', 'stroke-width' => 0.5, 'rx' => 3, 'ry' => 3,];
$colors = ['#1D4350', '#A43931'];

$svg = new SVG(800, 800);

$defs = new Defs($svg);
$g1 = new G($svg);


$linearGradient = UniformGradient::verticalFromTop($defs, $colors);
$rect = (new Rect($g1, 100, 150, 3, 3))->apply($style)->applyGradient($linearGradient);
(new Text($g1, "Top Bottom"))->apply(['x' => 3, 'y' => $rect->height + 20]);

$verticalFromBottom = UniformGradient::verticalFromBottom($defs, $colors);
$rect2 = (new Rect($g1, 100, 150, 160, 3))->apply($style)->applyGradient($verticalFromBottom);
(new Text($g1, "Bottom Top"))->apply(['x' => $rect2->x, 'y' => $rect2->height + 20]);

$diagonalFromTopLeft = UniformGradient::diagonalFromTopLeft($defs, ['#D38312', '#A83279']);
$rect3 = (new Rect($g1, 100, 150, 3, $rect2->height + 33))->apply($style)->applyGradient($diagonalFromTopLeft);
(new Text($g1, "From Top Left"))->apply(['x' => $rect3->x, 'y' => 250]);

$diagonalFromBottomRight = UniformGradient::diagonalFromBottomRight($defs, ['#D38312', '#A83279']);
$rect3 = (new Rect($g1, 100, 150, $rect2->x, $rect2->height + 33))->apply($style)->applyGradient($diagonalFromBottomRight);
(new Text($g1, "From Bottom Right"))->apply(['x' => $rect3->x, 'y' => 250]);

$diagonalFromBottomLeft = UniformGradient::diagonalFromBottomLeft($defs, ['#D38312', '#A83279']);
$rect3 = (new Rect($g1, 100, 150, 316, $rect2->height + 33))->apply($style)->applyGradient($diagonalFromBottomLeft);
(new Text($g1, "From Bottom Left"))->apply(['x' => $rect3->x, 'y' => 250]);

$diagonalFromTopRight = UniformGradient::diagonalFromTopRight($defs, ['#D38312', '#A83279']);
$rect3 = (new Rect($g1, 100, 150, 471, $rect2->height + 33))->apply($style)->applyGradient($diagonalFromTopRight);
(new Text($g1, "From Top Right"))->apply(['x' => $rect3->x, 'y' => 250]);

$horizontalFromLeft = UniformGradient::horizontalFromLeft($defs, ['#780206', '#061161']);
$rect3 = (new Rect($g1, 100, 150, 3, 2 * $rect2->height + 66))->apply($style)->applyGradient($horizontalFromLeft);
(new Text($g1, "From Left Right"))->apply(['x' => $rect3->x, 'y' => 385]);

$horizontalFromRight = UniformGradient::horizontalFromRight($defs, ['#780206', '#061161']);
$rect3 = (new Rect($g1, 100, 150, 160, 2 * $rect2->height + 66))->apply($style)->applyGradient($horizontalFromRight);
(new Text($g1, "From Right Left"))->apply(['x' => $rect3->x, 'y' => 385]);

$radialTopLeft = UniformGradient::radialTopLeft($defs, ['#000', '#e74c3c']);
$rect3 = (new Rect($g1, 100, 150, 3, 3 * $rect2->height + 96))->apply($style)->applyGradient($radialTopLeft);
(new Text($g1, "Radial Top Left"))->apply(['x' => $rect3->x, 'y' => 515]);


$radialTopRight = UniformGradient::radialTopRight($defs, ['#000', '#e74c3c']);
$rect3 = (new Rect($g1, 100, 150, 160, 3 * $rect2->height + 96))->apply($style)->applyGradient($radialTopRight);
(new Text($g1, "Radial Top Right"))->apply(['x' => $rect3->x, 'y' => 515]);

$radialBottomRight = UniformGradient::radialBottomRight($defs, ['#000', '#e74c3c']);
$rect3 = (new Rect($g1, 100, 150, 316, 3 * $rect2->height + 96))->apply($style)->applyGradient($radialBottomRight);
(new Text($g1, "Radial Bottom Right"))->apply(['x' => $rect3->x, 'y' => 515]);


$radialBottomLeft = UniformGradient::radialBottomLeft($defs, ['#000', '#e74c3c']);
$rect3 = (new Rect($g1, 100, 150, 471, 3 * $rect2->height + 96))->apply($style)->applyGradient($radialBottomLeft);
(new Text($g1, "Radial Bottom Left"))->apply(['x' => $rect3->x, 'y' => 515]);


$radialTopCenter = UniformGradient::radialTopCenter($defs, ['#000', '#e74c3c']);
$rect3 = (new Rect($g1, 100, 150, 3, 4 * $rect3->height + 126))->apply($style)->applyGradient($radialTopCenter);
(new Text($g1, "Radial Top Center"))->apply(['x' => $rect3->x, 'y' => 645]);

$radialLeftCenter = UniformGradient::radialLeftCenter($defs, ['#000', '#e74c3c']);
$rect3 = (new Rect($g1, 100, 150, 160, 4 * $rect3->height + 126))->apply($style)->applyGradient($radialLeftCenter);
(new Text($g1, "Radial Left Center"))->apply(['x' => $rect3->x, 'y' => 645]);


$radialBottomCenter = UniformGradient::radialBottomCenter($defs, ['#000', '#e74c3c']);
$rect3 = (new Rect($g1, 100, 150, 316, 4 * $rect2->height + 126))->apply($style)->applyGradient($radialBottomCenter);
(new Text($g1, "Radial Bottom Center"))->apply(['x' => $rect3->x, 'y' => 645]);


$radialBottomRight = UniformGradient::radialRightCenter($defs, ['#000', '#e74c3c']);
$rect3 = (new Rect($g1, 100, 150, 471, 4 * $rect2->height + 126))->apply($style)->applyGradient($radialBottomRight);
(new Text($g1, "Radial Right Center"))->apply(['x' => $rect3->x, 'y' => 645]);

echo $svg->draw();