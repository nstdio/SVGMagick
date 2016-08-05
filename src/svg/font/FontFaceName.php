<?php
namespace nstdio\svg\font;

/**
 * Class FontFaceName
 * The ‘font-face-name’ element is used within a ‘font-face-src’ element to reference a local font by name. It
 * corresponds to a local(…) clause in an @font-face rule ‘src’ descriptor.
 *
 * @property string name = "<anything>" The ‘name’ attribute specifies the name of a local font. Unlike the syntax
 *           allowed between the parentheses of the local(…) clause in an @font-face rule ‘src’ descriptor, the font
 *           name specified in this attribute is not surrounded in single or double quotes. Refer to the description of
 *           the 'src' descriptor in CSS2 for details on how the font name is interpreted ([CSS2], section 15.3.5).
 * @package nstdio\svg\font
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class FontFaceName extends BaseFont
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "font-face-name";
    }

}