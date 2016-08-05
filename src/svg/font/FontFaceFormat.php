<?php
namespace nstdio\svg\font;

/**
 * Class FontFaceFormat
 * Child ‘font-face-format’ elements of a ‘font-face-uri’ element are used to specify the supported formats of the font
 * referenced by that ‘font-face-uri’ element. They correspond to entries in a format(…) clause of the ‘src’ descriptor
 * in an @font-face rule.
 *
 * @property string string = "<anything>"
 *          The ‘string’ attribute is a hint to the user agent, and specifies a list of formats that the font
 *          referenced by the parent ‘font-face-uri’ element supports. The syntax of the attribute value is a format
 *          string as defined in CSS2, such as 'truetype'. Refer to the description of the 'src' descriptor in CSS2 for
 *          details on how the format hint is interpreted ([CSS2], section 15.3.5).
 * @package nstdio\svg\font
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class FontFaceFormat extends BaseFont
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "font-face-format";
    }

}