# SVGMagick [![Build Status](https://travis-ci.org/nstdio/SVGMagick.svg?branch=master)](https://travis-ci.org/nstdio/SVGMagick) [![Total Downloads](https://poser.pugx.org/nstdio/svg/downloads)](https://packagist.org/packages/nstdio/svg) [![License](https://poser.pugx.org/nstdio/svg/license)](https://packagist.org/packages/nstdio/svg)

The full SVG implementation on PHP. Currently, the library contains the full SVG specification. SVGMagick also contains a number of useful methods for working with filter, the gradient (e.g. UniformGradient with direction and position) and the transformation of the SVG basic shapes (e.g. polygon, rect, polyline) in the path. SVGMagick does not yet have a stable API, so everything can change at any time.

Hope to read feedbacks and suggestions from community about SVGMagick to make it better.

# Requirements

- php >= 5.4.0
- ext-dom (enabled by default)

# Installation
```
$ composer require nstdio/svg: "dev-master"
```
or add
```
"nstdio/svg": "dev-master"
```
to the `require` section of your `composer.json` file.
