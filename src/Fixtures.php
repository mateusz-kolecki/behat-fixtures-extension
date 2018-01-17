<?php

namespace MKolecki\Behat\FixturesExtension;

use ArrayAccess;
use InvalidArgumentException;
use LogicException;

final class Fixtures implements ArrayAccess
{
    /** @var string */
    private $delimiter;

    /** @var array */
    private $data;

    public function __construct(array $data, $delimiter = '/')
    {
        $this->data = $data;
        $this->delimiter = $delimiter;
    }

    /**
     * @param string $delimiter
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * Get value using path.
     *
     * @param string $path
     *
     * @return Fixtures|mixed
     * @throws InvalidArgumentException
     */
    public function get($path)
    {
        $pathArray = array_filter(explode($this->delimiter, $path));
        $value = $this->data;

        foreach ($pathArray as $pathNode) {
            if (!array_key_exists($pathNode, $value)) {
                throw new InvalidArgumentException("Could not find path $path");
            }

            $value = $value[$pathNode];
        }

        if ($this->isAssocArray($value)) {
            return new static($value, $this->delimiter);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        try {
            $this->get($offset);
            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new LogicException('Setting values on fixtures is not supported!');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new LogicException('Unset value on fixtures is not supported!');
    }

    /**
     * Return array representation of fixtures.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Check if given value is assoc array.
     *
     * Assoc array is when value is array and there is no int keys. Empty array is also assoc.
     *
     * @param mixed $value
     *
     * @return bool true when array is assoc
     */
    private function isAssocArray($value)
    {
        return is_array($value) &&
               !array_filter(array_keys($value),'is_int');
    }
}
