<?php
namespace nstdio\svg;
use nstdio\svg\container\ContainerInterface;

/**
 * interface ElementInterface
 *
 * @package nstdio\svg
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface ElementInterface
{
    /**
     * @return \DOMElement | ContainerInterface
     */
    public function getRoot();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return \DOMElement | ContainerInterface | XMLDocumentInterface
     */
    public function getElement();

    /**
     * @param array $assoc
     *
     * @return ElementInterface
     */
    public function apply(array $assoc);
}