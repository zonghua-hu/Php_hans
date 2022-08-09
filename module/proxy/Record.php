<?php

namespace proxy;

class Record
{
    /**
     * @var array
     */
    private $data;

    /**
     * Record constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function __set(string $name, string $value)
    {
        $this->data[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    public function __get(string $name): string
    {
        return $this->data[$name] ?? '';
    }
}
