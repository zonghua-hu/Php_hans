<?php

namespace proxy;

use phpDocumentor\Reflection\Types\True_;

class RecordProxy extends Record
{
    private $isDirty = false;

    private $isInitialized = false;

    public function __construct(array $data)
    {
        parent::__construct($data);
        if (count($data) > 0) {
            $this->isInitialized = true;
            $this->isDirty = true;
        }
    }

    /**
     * @return bool
     */
    public function isDirty(): bool
    {
        return $this->isDirty;
    }

    /**
     * @param string $name
     * @param string $value
     * @return RecordProxy
     */
    public function __set(string $name, string $value)
    {
        $this->isDirty = true;
        return parent::__set($name, $value);
    }
}
