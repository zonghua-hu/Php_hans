<?php

namespace bridge;

abstract class Service
{
    protected $implementation;
    public function __construct(FormatterInterface $formatter)
    {
        $this->implementation = $formatter;
    }

    public function setImplementation(FormatterInterface $formatter)
    {
        $this->implementation = $formatter;
    }
    abstract public function getFormatter(string $formatString);
}
