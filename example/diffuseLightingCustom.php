<?php

use nstdio\svg\container\Defs;
use nstdio\svg\container\SVG;
use nstdio\svg\filter\Composite;
use nstdio\svg\filter\DiffuseLighting;
use nstdio\svg\filter\Filter;
use nstdio\svg\light\DistantLight;
use nstdio\svg\light\SpotLight;
use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Rect;
use nstdio\svg\text\Text;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG(440, 240);

$defs = new Defs($svg);

(new Text($svg, 'No Light'))->apply(['x' => 60, 'y' => 22, 'text-anchor' => 'middle']);
(new Circle($svg, 60, 80, 50))->apply(['fill' => 'green']);

(new Text($svg, 'fePointLight'))->apply(['x' => 170, 'y' => 22, 'text-anchor' => 'middle']);

$filter = DiffuseLighting::diffusePointLight($svg, ['x' => 150, 'y' => 60, 'z' => 20]);

(new Circle($svg, 170, 80, 50))->apply(['fill' => 'green'])->applyFilter($filter);

(new Text($svg, 'feDistantLight'))->apply(['x' => 280, 'y' => 22, 'text-anchor' => 'middle']);

$filter2 = new Filter($svg);
$diffLight2 = (new DiffuseLighting($filter2))->apply(['result' => 'light', 'lighting-color' => 'white']);
(new DistantLight($diffLight2))->apply(['azimuth' => 240, 'elevation' => 50]);
(new Composite($filter2))->apply(['in2' => $diffLight2->result, 'operator' => 'arithmetic', 'k1' => 1]);
(new Circle($svg, 280, 80, 50))->apply(['fill' => 'green'])->applyFilter($filter2);


(new Text($svg, 'feSpotLight'))->apply(['x' => 390, 'y' => 22, 'text-anchor' => 'middle']);

$filter3 = new Filter($svg);
$diffLight3 = (new DiffuseLighting($filter3))->apply(['result' => 'light', 'lighting-color' => 'white']);
(new SpotLight($diffLight3))->apply(['x' => 360, 'y' => 5, 'z' => 30, 'limitingConeAngle' => 20, 'pointsAtX' => 390, 'pointsAtY' => '80', 'pointsAtZ' => '0']);
(new Composite($filter3))->apply(['in2' => $diffLight3->result, 'operator' => 'arithmetic', 'k1' => 1]);
(new Circle($svg, 390, 80, 50))->apply(['fill' => 'green'])->applyFilter($filter3);

$rect = (new Rect($svg, 100, 100, 10, 140))
    ->apply(['fill' => 'green'])
    ->diffusePointLight(['x' => 50, 'y' => 50, 'z' => 30], []);
$rect->setBorderRadius(5);

echo $svg->draw();