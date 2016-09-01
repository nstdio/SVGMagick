<?php
namespace nstdio\svg;

use DOMDocument;
use DOMElement;
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
     * Returns the parent element.
     *
     * @return ContainerInterface|DOMDocument
     */
    public function getRoot();

    /**
     * The name of element.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the element itself.
     *
     * @return ContainerInterface|XMLDocumentInterface|DOMElement
     */
    public function getElement();

    /**
     * @param array $assoc An associative array, where the key represents the attributes and the value is the value
     *                     of attribute. Note that some attribute names can be converted.
     * @see SVGElement::$notConvertable
     * @return ElementInterface
     */
    public function apply(array $assoc);

    /**
     * Makes copy of that element. In general this method is intended to replace [[__clone()]] magic method.
     * If you are not passing the element 'id', in the first parameter it will be generated automatically.
     *
     * Note that this method will not performs deep copy of object.
     *
     * @param array                   $apply  This parameter will be passed as argument to [[apply()]] method.
     * @param array                   $ignore List of elements that should not be copied from source object. Anyway
     *                                        'id' will be added to this list.
     *
     * @param null|ContainerInterface $parent
     *
     * @return ElementInterface
     */
    public function copy(array $apply = [], array $ignore = [], ContainerInterface $parent = null);
}