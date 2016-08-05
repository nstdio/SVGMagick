<?php
use nstdio\svg\container\G;
use nstdio\svg\container\SVG;
use nstdio\svg\desc\Desc;
use nstdio\svg\filter\Composite;
use nstdio\svg\filter\Filter;
use nstdio\svg\filter\GaussianBlur;
use nstdio\svg\filter\SpecularLighting;
use nstdio\svg\light\PointLight;
use nstdio\svg\shape\Rect;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG();
$svg->apply(['viewBox' => '0 0 1100 400']);

new Desc($svg, 'Filter example');

$filter = new Filter($svg);

$blur = new GaussianBlur($filter);
$blur->apply(['in' => 'SourceAlpha', 'stdDeviation' => 4, 'result' => 'blur1']);

$specularLight = new SpecularLighting($filter);
$specularLight->apply(['result' => 'specOut', 'in' => $blur->result, 'specularExponent' => 20, 'lighting-color' => '#bbbbbb']);

$pointLight = new PointLight($specularLight);
$pointLight->apply(['x' => 50, 'y' => 100, 'z' => 200]);

$composite = new Composite($filter);
$composite->apply(['in' => "SourceGraphic", 'in2' => $specularLight->result, 'operator' => "arithmetic", 'k1' => 0, 'k2' => 1, 'k3' => 1, 'k4' => 0]);

$g = new G($svg);
$g->apply(['stroke' => "tomato", 'fill' => "peru", 'filter' => "url(#$filter->id)"]);

$params = [
    ['x' => '10%', 'y' => '10%'],
    ['x' => '55%', 'y' => '10%'],
    ['x' => '10%', 'y' => '55%'],
    ['x' => '55%', 'y' => '55%'],
];

foreach ($params as $param) {
    new Rect($g, '40%', '40%', $param['x'], $param['y']);
}

echo $svg->draw();