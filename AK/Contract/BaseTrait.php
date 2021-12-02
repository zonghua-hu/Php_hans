<?php

namespace Contract;

use http\Exception\BadMethodCallException;

/**
 * Class BaseTrait
 * @package Contract
 */
trait BaseTrait
{
    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (strpos($name, 'get') !== false) {
            $field = lcfirst(ltrim($name, 'get'));
            if (property_exists($this, $field)) {
                return $this->{$field};
            }
        } elseif (strpos($name, 'set') !== false) {
            $field = lcfirst(ltrim($name, 'set'));
            if (property_exists($this, $field)) {
                $this->{$field} = array_key_exists(0, $arguments) ? $arguments[0] : null;
                return $this;
            }
        }
        $className = get_class($this);
        throw new BadMethodCallException("Call to undefined method {$className}::{$name}()");
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $return = [];
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $method = 'get' . ucfirst($property->name);
            $value = $this->{$method}();
            if (is_null($value)) {
                continue;
            }
            $return[$property->name] = $value;
        }
        return $return;
    }
}
