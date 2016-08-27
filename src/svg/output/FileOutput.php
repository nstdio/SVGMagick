<?php
namespace nstdio\svg\output;

use Gaufrette\Adapter\Local;
use Gaufrette\Filesystem;

/**
 * Class FileOutput
 *
 * @package nstdio\svg\output
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class FileOutput implements FileOutputInterface
{
    /**
     * @var Local
     */
    private $adapter;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @inheritdoc
     */
    public function file($name, $content, $override)
    {
        $this->lazyInit($name);

        $this->defineContentType($name, $content);

        return $this->filesystem->write($name, $content, $override);
    }

    private function lazyInit($fileName)
    {
        if ($this->shouldInit()) {
            $this->adapter = new Local(dirname($fileName), true);
            $this->filesystem = new Filesystem($this->adapter);
        }
    }

    /**
     * @return bool
     */
    private function shouldInit()
    {
        return $this->adapter === null && $this->filesystem === null;
    }

    private function defineContentType(&$name, &$content)
    {
        $info = pathinfo($name);
        if (isset($info['extension']) === false) {
            $info['extension'] = 'svg';
        }

        if (strtolower($info['extension']) === 'svgz') {
            $content = gzencode($content, 9);
        }

        $name = $info['filename'] . "." . $info['extension'];
    }
}