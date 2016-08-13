<?php
namespace nstdio\svg\container;

use nstdio\svg\SVGElement;

/**
 * Class Container
 *
 * @property string     enableBackground
 * @property string     fontFamily   This property indicates which font family is to be used to render the text,
 *           specified as a prioritized list of font family names and/or generic family names.
 * @property string     fontSize     This property refers to the size of the font from baseline to baseline when
 *           multiple lines of text are set solid in a multiline layout environment. For SVG, if a <length> is provided
 *           without a unit identifier (e.g., an unqualified number such as 128), the SVG user agent processes the
 *           <length> as a height value in the current user coordinate system.
 * @property string|int fontWeight
 * @property float      fillOpacity  specifies the opacity of the painting operation used to paint the interior the
 *           current object.
 * @package nstdio\svg\container
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class Container extends SVGElement
{

}