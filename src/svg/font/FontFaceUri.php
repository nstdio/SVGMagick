<?php
namespace nstdio\svg\font;

/**
 * Class FontFaceUri
 * The ‘font-face-uri’ element is used within a ‘font-face-src’ element to reference a font defined inside or outside
 * of the current SVG document.
 *
 * When a ‘font-face-uri’ is referencing an SVG font, then that reference must be to an SVG ‘font’ element, therefore
 * requiring the use of a fragment identifier [RFC3986]. The referenced ‘font’ element can be local (i.e., within the
 * same document as the ‘font-face-uri’ element) or remote (i.e., within a different document).
 *
 * @link    https://www.w3.org/TR/SVG11/fonts.html#FontFaceURIElement
 *
 * @property string xlink:href = "<IRI>" The ‘xlink:href’ attribute specifies the location of the referenced font.
 *
 * @package nstdio\svg\font
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class FontFaceUri extends BaseFont
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "font-face-uri";
    }

}