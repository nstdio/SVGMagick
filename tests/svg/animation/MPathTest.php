<?php

use nstdio\svg\animation\AnimateMotion;
use nstdio\svg\animation\MPath;
use nstdio\svg\shape\Path;

class MPathTest extends SVGContextTestCase
{
    /**
     * @var AnimateMotion
     */
    private $motionObj;

    /**
     * @var MPath
     */
    private $mpathObj;

    /**
     * @var Path
     */
    private $pathObj;

    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->id = 'path1';
        $this->pathObj = new Path($this->svgObj, 10, 20);
        $this->pathObj->id = $this->id;

        $this->mpathObj = new MPath($this->svgObj);
        $this->mpathObj->setXLinkAttribute('href', "#" . $this->pathObj->id);
        $this->motionObj = new AnimateMotion($this->svgObj, $this->mpathObj);
    }

    public function testId()
    {
        self::assertEquals("#" . $this->id, $this->mpathObj->getXLinkAttribute('href'));
    }


}
