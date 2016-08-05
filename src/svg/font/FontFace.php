<?php
namespace nstdio\svg\font;

/**
 * Class FontFace
 * The ‘font-face’ element corresponds directly to the @font-face facility in CSS2 ([CSS2], section 15.3.1). It can be
 * used to describe the characteristics of any font, SVG font or otherwise.
 *
 * When used to describe the characteristics of an SVG font contained within the same document, it is recommended that
 * the ‘font-face’ element be a child of the ‘font’ element it is describing so that the ‘font’ element can be
 * self-contained and fully-described. In this case, any ‘font-face-src’ elements within the ‘font-face’ element are
 * ignored as it is assumed that the ‘font-face’ element is describing the characteristics of its parent ‘font’
 * element.
 *
 * @property string     fontFamily             = "<string>" Same syntax and semantics as the ‘font-family’ descriptor
 *           within an @font-face rule.
 * @property string     fontStyle              = "all | [ normal | italic | oblique] [, [normal | italic | oblique]]*"
 *           Same syntax and semantics as the ‘font-style’ descriptor within an @font-face rule. The style of a font.
 *           Takes on the same values as the ‘font-style’ property, except that a comma-separated list is permitted. If
 *           the attribute is not specified, the effect is as if a value of 'all' were specified.
 * @property string     fontVariant            = "[normal | small-caps] [,[normal | small-caps]]*" Same syntax and
 *           semantics as the ‘font-variant’ descriptor within an @font-face rule. Indication of whether this face is
 *           the small-caps variant of a font. Takes on the same values as the ‘font-variant’ property, except that a
 *           comma-separated list is permitted. If the attribute is not specified, the effect is as if a value of
 *           'normal' were specified.
 * @property string|int fontWeight             = "all | [normal | bold | 100 | 200 | 300 | 400 | 500 | 600 | 700 | 800
 *           | 900] [, [normal | bold | 100 | 200 | 300 | 400 | 500 | 600 | 700 | 800 | 900]]*" Same syntax and
 *           semantics as the ‘font-weight’ descriptor within an @font-face rule. The weight of a face relative to
 *           others in the same font family. Takes on the same values as the ‘font-weight’ property with three
 *           exceptions: relative keywords (bolder, lighter) are not permitted a comma-separated list of values is
 *           permitted, for fonts that contain multiple weights an additional keyword, 'all', is permitted, which means
 *           that the font will match for all possible weights; either because it contains multiple weights, or because
 *           that face only has a single weight. If the attribute is not specified, the effect is as if a value of
 *           'all' were specified.
 * @property string     fontStretch            = "all | [ normal | ultra-condensed | extra-condensed | condensed |
 *           semi-condensed | semi-expanded | expanded | extra-expanded | ultra-expanded] [, [ normal | ultra-condensed
 *           | extra-condensed | condensed | semi-condensed | semi-expanded | expanded | extra-expanded |
 *           ultra-expanded] ]*" Same syntax and semantics as the ‘font-stretch’ descriptor within an @font-face rule.
 *           Indication of the condensed or expanded nature of the face relative to others in the same font family.
 *           Takes on the same values as the ‘font-stretch’ property except that: relative keywords (wider, narrower)
 *           are not permitted a comma-separated list is permitted the keyword 'all' is permitted If the attribute is
 *           not specified, the effect is as if a value of 'normal' were specified.
 * @property string     fontSize               = "<string>" Same syntax and semantics as the ‘font-size’ descriptor
 *           within an @font-face rule.
 * @property string     unicodeRange           = "<urange> [, <urange>]*" Same syntax and semantics as the
 *           ‘unicode-range’ descriptor within an @font-face rule. The range of ISO 10646 characters [UNICODE] possibly
 *           covered by the glyphs in the font. Except for any additional information provided in this specification,
 *           the normative definition of the attribute is in CSS2 ([CSS2], section 15.3.3). If the attribute is not
 *           specified, the effect is as if a value of 'U+0-10FFFF' were specified.
 * @property string     unitsPerEm             = "<number>" Same syntax and semantics as the ‘units-per-em’ descriptor
 *           within an @font-face rule. The number of coordinate units on the em square, the size of the design grid on
 *           which glyphs are laid out. This value is almost always necessary as nearly every other attribute requires
 *           the definition of a design grid. If the attribute is not specified, the effect is as if a value of '1000'
 *           were specified.
 * @property int        panose                 -1 = "[<integer>]{10}" Same syntax and semantics as the ‘panose-1’
 *           descriptor within an @font-face rule. The Panose-1 number, consisting of ten decimal integers, separated
 *           by whitespace. Except for any additional information provided in this specification, the normative
 *           definition of the attribute is in CSS2 ([CSS2], section 15.3.6). If the attribute is not specified, the
 *           effect is as if a value of '0 0 0 0 0 0 0 0 0 0' were specified.
 * @property float      stemv                  = "<number>" Same syntax and semantics as the ‘stemv’ descriptor within
 *           an @font-face rule.
 * @property float      stemh                  = "<number>" Same syntax and semantics as the ‘stemh’ descriptor within
 *           an @font-face rule.
 * @property float      slope                  = "<number>" Same syntax and semantics as the ‘slope’ descriptor within
 *           an @font-face rule. The vertical stroke angle of the font. Except for any additional information provided
 *           in this specification, the normative definition of the attribute is in CSS2 ([CSS2], section 15.3.6). If
 *           the attribute is not specified, the effect is as if a value of '0' were specified.
 * @property float      capHeight              = "<number>" Same syntax and semantics as the ‘cap-height’ descriptor
 *           within an @font-face rule. The height of uppercase glyphs in the font within the font coordinate system.
 * @property float      xHeight                = "<number>" Same syntax and semantics as the ‘x-height’ descriptor
 *           within an @font-face rule. The height of lowercase glyphs in the font within the font coordinate system.
 * @property float      accentHeight           = "<number>" The distance from the origin to the top of accent
 *           characters, measured by a distance within the font coordinate system. If the attribute is not specified,
 *           the effect is as if the attribute were set to the value of the ‘ascent’ attribute.
 * @property float      ascent                 = "<number>" Same syntax and semantics as the ‘ascent’ descriptor within
 *           an @font-face rule. The maximum unaccented height of the font within the font coordinate system. If the
 *           attribute is not specified, the effect is as if the attribute were set to the difference between the
 *           ‘units-per-em’ value and the ‘vert-origin-y’ value for the corresponding font.
 * @property float      descent                = "<number>" Same syntax and semantics as the ‘descent’ descriptor
 *           within an @font-face rule. The maximum unaccented depth of the font within the font coordinate system. If
 *           the attribute is not specified, the effect is as if the attribute were set to the ‘vert-origin-y’ value
 *           for the corresponding font.
 * @property string     widths                 = "<string>" Same syntax and semantics as the ‘widths’ descriptor within
 *           an @font-face rule.
 * @property string     bbox                   = "<string>" Same syntax and semantics as the ‘bbox’ descriptor within
 *           an @font-face rule.
 * @property float      ideographic            = "<number>" For horizontally oriented glyph layouts, indicates the
 *           alignment coordinate for glyphs to achieve ideographic baseline alignment. The value is an offset in the
 *           font coordinate system.
 * @property float      alphabetic             = "<number>" Same syntax and semantics as the ‘baseline’ descriptor
 *           within an @font-face rule. For horizontally oriented glyph layouts, indicates the alignment coordinate for
 *           glyphs to achieve alphabetic baseline alignment. The value is an offset in the font coordinate system.
 * @property float      mathematical           = "<number>" Same syntax and semantics as the ‘mathline’ descriptor
 *           within an @font-face rule. For horizontally oriented glyph layouts, indicates the alignment coordinate for
 *           glyphs to achieve mathematical baseline alignment. The value is an offset in the font coordinate system.
 * @property float      hanging                = "<number>" For horizontally oriented glyph layouts, indicates the
 *           alignment coordinate for glyphs to achieve hanging baseline alignment. The value is an offset in the font
 *           coordinate system.
 * @property float      vIdeographic           = "<number>" For vertically oriented glyph layouts, indicates the
 *           alignment coordinate for glyphs to achieve ideographic baseline alignment. The value is an offset in the
 *           font coordinate system relative to the glyph-specific ‘vert-origin-x’ attribute.
 * @property float      vAlphabetic            = "<number>" For vertically oriented glyph layouts, indicates the
 *           alignment coordinate for glyphs to achieve alphabetic baseline alignment. The value is an offset in the
 *           font coordinate system relative to the glyph-specific ‘vert-origin-x’ attribute.
 * @property float      vMathematical          = "<number>" For vertically oriented glyph layouts, indicates the
 *           alignment coordinate for glyphs to achieve mathematical baseline alignment. The value is an offset in the
 *           font coordinate system relative to the glyph-specific ‘vert-origin-x’ attribute.
 * @property float      vHanging = "<number>" For vertically oriented glyph layouts, indicates
 *           the alignment coordinate for glyphs to achieve hanging baseline alignment. The value is an offset in the
 *           font coordinate system relative to the glyph-specific ‘vert-origin-x’ attribute.
 * @property float      underlinePosition      = "<number>" The ideal position of an underline within the font
 *           coordinate system.
 * @property float      underlineThickness     = "<number>" The ideal thickness of an underline, expressed as a length
 *           within the font coordinate system.
 * @property float      strikethroughPosition  = "<number>" The ideal position of a strike-through within the font
 *           coordinate system.
 * @property float      strikethroughThickness = "<number>" The ideal thickness of a strike-through, expressed as a
 *           length within the font coordinate system.
 * @property float      overlinePosition       = "<number>" The ideal position of an overline within the font
 *           coordinate system.
 * @property float      overlineThickness      = "<number>" The ideal thickness of an overline, expressed as a length
 *           within the font coordinate system.
 * @package  nstdio\svg\font
 * @author   Edgar Asatryan <nstdio@gmail.com>
 */
class FontFace extends BaseFont
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "font-face";
    }

}