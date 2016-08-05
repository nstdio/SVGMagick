<?php
use nstdio\svg\desc\Desc;
use nstdio\svg\desc\Metadata;
use nstdio\svg\desc\Title;

class DescriptiveTest extends DOMContextTest
{
    /**
     * @var Desc
     */
    private $descObj;

    /**
     * @var Metadata
     */
    private $metadataObj;

    /**
     * @var Title
     */
    private $titleObj;

    /**
     * @var string
     */
    private $text;

    public function setUp()
    {
        parent::setUp();
        $this->text = 'Test Text';
        $this->descObj = new Desc($this->svgObj, $this->text);
        $this->metadataObj = new Metadata($this->svgObj, $this->text);
        $this->titleObj = new Title($this->svgObj, $this->text);
    }

    public function testContent()
    {
        self::assertEquals($this->text, $this->descObj->getElement()->nodeValue);
        self::assertEquals($this->text, $this->metadataObj->getElement()->nodeValue);
        self::assertEquals($this->text, $this->titleObj->getElement()->nodeValue);
    }
}
