<?php

use nstdio\svg\container\Defs;
use nstdio\svg\container\SVG;
use nstdio\svg\filter\Filter;
use nstdio\svg\filter\Image;
use nstdio\svg\text\Text;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG();

$defs = new Defs($svg);
$shadow = Filter::shadow($defs, 5, -1, 1.5);

$text = (new Text($svg, 'SVG CORE'))->apply([
    'x'            => 100,
    'y'            => 50,
    'font-size'    => 45,
    'stroke'       => 'blue',
    'stroke-width' => 0.3,
    'fill'         => '#0047AB',
    'filter'       => "url(#$shadow->id)",
]);

$grayScale = Filter::grayScale($defs, 10);

$image = (new Image($svg))->apply([
    'xlink:href' => 'http://www.menucool.com/slider/jsImgSlider/images/image-slider-2.jpg',
    'y' => '70',
    'width' => '40%',
    'height' => '40%',
    'filter' => "url(#$grayScale->id)",
]);

echo $svg->draw();