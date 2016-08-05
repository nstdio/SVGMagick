<?php
use nstdio\svg\container\Defs;
use nstdio\svg\container\G;
use nstdio\svg\container\SVG;
use nstdio\svg\desc\Desc;
use nstdio\svg\desc\Title;
use nstdio\svg\filter\Blend;
use nstdio\svg\filter\Filter;
use nstdio\svg\gradient\UniformGradient;
use nstdio\svg\shape\Rect;
use nstdio\svg\text\Text;
use nstdio\svg\util\KeyValueWriter;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG('5cm', '5cm');
$svg->getElement()->setAttribute('viewBox', "0 0 500 500");
$title = new Title($svg, 'Example feBlend - Examples of feBlend modes');
$desc = new Desc($svg, 'Five text strings blended into a gradient, with one text string for each of the five feBlend modes.');
$svg->append($title, $desc);

$defs = new Defs($svg);

$linearGradient = UniformGradient::uniformGradient($defs, ['#000000', '#ffffff', '#ff0000', '#808080'], null);
KeyValueWriter::apply($linearGradient->getElement(), ['gradientUnits' => 'userSpaceOnUse', 'x1' => 100, 'y1' => 0, 'x2' => 300, 'y2' => 0,]);


$defs->append($linearGradient);
$types = ['Normal', 'Multiply', 'Screen', 'Darken', 'Lighten'];

foreach ($types as $type) {
    $blend = new Blend($defs);
    $blend->mode = strtolower($type);
    $blend->in2 = 'BackgroundImage';
    $blend->in = 'SourceGraphic';
    $filter = new Filter($defs, $type, $blend);

    $defs->append($filter);
}

$svg->append($defs);

$rect = new Rect($svg, 498, 498, 1, 1);
$rect->fill = "none";
$rect->stroke = "blue";

$g = new G($svg);
$g->enableBackground = "new";

$rect2 = new Rect($g, 460, 300, 100, 20);
$rect2->fill = "url(#$linearGradient->id)";

$g->append($rect2);

$gInner = new G($g);
$gInner->fontFamily = "Verdana";
$gInner->fontSize = 75;
$gInner->fill = "#888888";
$gInner->fillOpacity = 0.6;

$textArray = [90, 180, 270, 360, 450];

foreach ($textArray as $key => $value) {
    $text = new Text($gInner, $types[$key]);
    $text->x = 50;
    $text->y = $value;
    $text->filter = "url(#" . $types[$key] . ")";

    $gInner->append($text);
}
$g->append($gInner);

$svg->append($g);
$svg->append($rect);

echo $svg->draw();