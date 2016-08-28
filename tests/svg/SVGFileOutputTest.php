<?php

use nstdio\svg\output\IOFormat;

class SVGFileOutputTest extends SVGContextTestCase
{
    private $fileName;

    private $gzFileName;

    private $outDir;

    public function setUp()
    {
        parent::setUp();

        $this->outDir = __DIR__ . '/out';
        $this->fileName = $this->outDir . '/test.svg';
        $this->gzFileName = $this->outDir . '/test.svgz';

        $this->cleanUp();
    }

    private function cleanUp()
    {
        array_map('unlink', glob(dirname($this->fileName) . "/*"));

        if (is_dir($this->outDir)) {
            rmdir($this->outDir);
        }
    }

    public function tearDown()
    {
        $this->cleanUp();
    }

    public function testAsFile()
    {
        $numBytes = $this->svgObj->asFile($this->fileName);
        $gzNumBytes = $this->svgObj->asFile($this->gzFileName);

        self::assertNotEquals(0, $numBytes);
        self::assertNotEquals(0, $gzNumBytes);
        self::assertFileExists($this->fileName);
        self::assertFileExists($this->gzFileName);

        $this->svgObj->asFile($this->fileName, false, true); // exception not thrown.
    }

    public function testDefaultExtension()
    {
        $fileName = $this->fileNameWithExt('');

        $numBytes = $this->svgObj->asFile($fileName);

        self::assertNotEquals(0, $numBytes);
        self::assertFileExists($this->fileName);
    }

    private function fileNameWithExt($ext)
    {
        return str_replace('.svg', $ext, $this->fileName);
    }

    public function testImageFile()
    {
        try {
            $imageFile = $this->fileNameWithExt(IOFormat::JPEG);

            $numBytes = $this->svgObj->asImageFile($imageFile, IOFormat::JPEG);

            self::assertNotEquals(0, $numBytes);
            self::assertFileExists($imageFile);

            $binImage = $this->svgObj->asImage(IOFormat::JPEG);
            $fileContent = file_get_contents($imageFile);

            //we cant perform binary safe string comparision because of automatically generated "svg:base-uri" by Imagick.
            self::assertEquals(strlen($binImage), strlen($fileContent));

        } catch (RuntimeException $e) {
            self::markTestSkipped($e->getMessage() . "\nSkipping...");
        }
    }
}
