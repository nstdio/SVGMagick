# SVGMagick [![Build Status](https://travis-ci.org/nstdio/SVGMagick.svg?branch=master)](https://travis-ci.org/nstdio/SVGMagick) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nstdio/SVGMagick/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nstdio/SVGMagick/?branch=master)

The full SVG implementation on PHP. Currently, the library contains the full SVG specification. SVGMagick also contains a number of useful methods for working with filter, the gradient (e.g. UniformGradient with direction and position) and the transformation of the SVG basic shapes (e.g. polygon, rect, polyline) in the path. SVGMagick does not yet have a stable API, so everything can change at any time.

Hope to read feedbacks and suggestions from community about SVGMagick to make it better.

# Requirements

- php >= 5.4.0
- ext-dom (enabled by default)

# Installation

The suggested installation method is via [composer](https://getcomposer.org/):
```
$ composer require nstdio/svg: "dev-master"
```
or add
```
"nstdio/svg": "dev-master"
```
to the `require` section of your `composer.json` file.

# Basic Usage

Every SVG tag has corresponding class in SVGMagick. What is needed for the creation of SVGMagick object? You have to pass as the first parameter of the constructor is the parent object. So you don't need to explicitly call `append` on parent object.
```php
use nstdio\svg\container\Defs;
use nstdio\svg\container\SVG;
use nstdio\svg\gradient\LinearGradient;

// ...

$svg = new SVG();
$defs = new Defs($svg); // <defs> will be appended in <svg>.
$gradient = new LinearGradient($defs) // <linearGradient> will be appended in <defs>

```
All classes are subject to this rule except for `SVG`.

## Shapes
```php

use nstdio\svg\container\SVG;
use nstdio\svg\shape\Rect;

// ...

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
    'strokeWidth' => 5,
]);

echo $svg; // or $svg->draw() to get svg structure;
```
Result
```html
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="640" height="480" version="1.1" viewBox="0 0 640 480">
    <rect height="120" width="300" x="3" y="3" rx="5" ry="5" stroke="darkgreen" fill="limegreen" stroke-width="1.5"></rect>
    <circle cx="75" cy="200" r="70" fill="#001f3f" fill-opacity="0.6" stroke="#FF851B" stroke-width="5"></circle>
</svg>
```
