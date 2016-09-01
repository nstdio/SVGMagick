<?php
namespace nstdio\svg;

use nstdio\svg\xml\DOMWrapper;

/**
 * Class Base
 *
 * @package nstdio\svg
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class Base
{
    /**
     *
     */
    const DOM_VERSION = '1.0';

    /**
     *
     */
    const DOM_ENCODING = 'utf-8';

    /**
     *
     */
    const XML_NS = 'http://www.w3.org/2000/svg';

    /**
     *
     */
    const XML_NS_XLINK = 'http://www.w3.org/1999/xlink';

    /**
     * @var XMLDocumentInterface
     */
    protected $domImpl;


    /**
     * Base constructor.
     *
     * @param XMLDocumentInterface|null $dom
     */
    public function __construct(XMLDocumentInterface $dom = null)
    {
        if (!extension_loaded('libxml')) {
            throw new \RuntimeException('The libxml extension is disabled. It is necessary to work with XML. You can try to recompile PHP with --with-libxml-dir=<location>.');
        }
        if ($dom !== null) {
            $this->domImpl = $dom;
        } else {
            if (DOMWrapper::isLoaded()) {
                $this->domImpl = new DOMWrapper();
            }
        }
    }

    /**
     * @param             $name
     * @param string|null $text
     *
     * @return XMLDocumentInterface
     */
    protected function element($name, $text = null)
    {
        return $this->domImpl->createElement($name, $text);
    }
}