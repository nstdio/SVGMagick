<?php
namespace nstdio\svg\output;
use Mimey\MimeTypes;

/**
 * Class ImageOutput
 *
 * @package nstdio\svg\output
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class ImageOutput implements ImageOutputInterface
{
    /**
     * @var MimeTypes
     */
    private static $mime;

    public function __construct()
    {
        self::$mime = new MimeTypes();
    }

    /**
     * @inheritdoc
     */
    public function imageFile($content, $name, $format, $override)
    {
        $imagick = $this->createImagick($content, $format);

        $fileWriter = new FileOutput();

        return $fileWriter->file($name, $imagick->getImageBlob(), $override);
    }

    /**
     * @param $content
     * @param $format
     *
     * @return \Imagick
     */
    private function createImagick($content, $format)
    {
        $this->checkExtensionLoaded();

        $imagick = new \Imagick();

        $imagick->readImageBlob($content);
        try {
            $imagick->setImageFormat($format);
        } catch (\ImagickException $e) {
            throw new \InvalidArgumentException(sprintf("Invalid image format %s.", $format), 0, $e);
        }

        return $imagick;
    }

    private function checkExtensionLoaded()
    {
        if (!extension_loaded('imagick')) {
            throw new \RuntimeException('Imagick extension is not loaded. If you access to php.ini try uncomment or add extension=php_imagick.so.');
        }
    }

    /**
     * @inheritdoc
     */
    public function image($content, $format, $sendHeader)
    {
        $imagick = $this->createImagick($content, $format);

        if ($sendHeader) {
            $this->trySendHeader($format);
        }

        return $imagick->getImageBlob();
    }

    private function trySendHeader($format)
    {
        if (!headers_sent()) {
            header($this->getContentType($format));
        }
    }

    private function getContentType($format)
    {
        return sprintf("Content-Type: %s", self::$mime->getMimeType($format));
    }
}