<?php
namespace nstdio\svg;

use nstdio\svg\container\ContainerInterface;
use nstdio\svg\util\Identifier;
use nstdio\svg\util\Inflector;
use nstdio\svg\util\KeyValueWriter;

/**
 * Class SVGElement
 *
 * @property string $id
 * @property string $fill The fill color.
 *
 * @package nstdio\svg
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class SVGElement implements ElementInterface, ElementFactoryInterface
{

    private static $notConvertable = ['tableValues', 'filterUnits', 'gradientUnits', 'viewBox', 'repeatCount', 'attributeName', 'attributeType', 'stdDeviation'];

    /**
     * @var XMLDocumentInterface | ElementInterface | ElementFactoryInterface | ContainerInterface
     */
    protected $root;

    /**
     * @var ElementInterface | XMLDocumentInterface
     */
    protected $element;

    public function __construct(ElementInterface $svg)
    {
        $this->root = $svg;

        $this->element = $this->createElement($this->getName());
        $this->add();
    }

    public function createElement($name, $value = null, $attributes = [])
    {
        return $this->root->createElement($name, $value, $attributes);
    }

    abstract public function getName();

    /**
     * @return ElementInterface
     */
    public function add()
    {
        $this->root->append($this);
    }

    final public function getRoot()
    {
        return $this->root;
    }

    final public function getElement()
    {
        return $this->element;
    }

    public function allAttributes(array $except = [])
    {
        return $this->element->attributes($except);
    }

    public function __get($name)
    {
        $name = Inflector::camel2dash($name);
        $value = $this->element->getAttribute($name);
        if ($value === '') {
            $value = $this->getXLinkAttribute($name);
        }

        return $value === '' ? null : $value;
    }

    public function __set($name, $value)
    {
        if ($name === 'id' && $value === null) {
            $this->element->setAttribute($name, Identifier::random('__' . $this->getName(), 5));

            return;
        }

        if ($value === null || $value === false || $value === '') {
            return;
        }
        $name = $this->convertAttributeName($name);
        $this->element->setAttribute($name, $value);
    }

    public function getXLinkAttribute($name)
    {
        return $this->element->getAttributeNS('xlink', $name);
    }

    /**
     * @param $name
     *
     * @return string
     */
    private function convertAttributeName($name)
    {
        return !in_array($name, self::$notConvertable) ? Inflector::camel2dash($name) : $name;
    }

    /**
     * @inheritdoc
     */
    public function apply(array $assoc)
    {
        $filtered = [];
        foreach ($assoc as $attribute => $value) {
            $filtered[$this->convertAttributeName($attribute)] = $value;
        }
        KeyValueWriter::apply($this->element, $filtered);

        return $this;
    }

    protected function setAttribute($name, $value, $xLink = false)
    {
        if ($xLink === true) {
            $this->element->setAttributeNS('xlink', $name, $value);
        } else {
            $this->element->setAttribute($name, $value);
        }
    }
}