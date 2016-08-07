<?php
namespace nstdio\svg;

/**
 * Class ChildStorage
 *
 * @package nstdio\svg
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class ElementStorage implements \Countable, \ArrayAccess, \Iterator
{
    private $data = [];

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->data[$offset];
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof ElementInterface)) {
            throw new \InvalidArgumentException(
                sprintf("The value must implement ElementInterface, %s given.", gettype($value))
            );
        }

        if (gettype($offset) !== 'integer' && $offset !== null) {
            throw new \InvalidArgumentException(
                sprintf("The offset must be integer, %s given.", gettype($offset))
            );
        }
        if (!in_array($value, $this->data, true)) {
            if ($offset === null) {
                $this->data[] = $value;
            } else {
                $this->data[$offset] = $value;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->data[$offset]);
            $this->data = array_values($this->data);
        }
    }

    public function remove(ElementInterface $element)
    {
        $key = array_search($element, $this->data, true);
        if ($key !== false) {
            $this->offsetUnset($key);
        }
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        return next($this->data);
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return key($this->data) !== null;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        return reset($this->data);
    }
}
