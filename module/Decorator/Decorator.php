<?php

namespace Decorator;

abstract class Decorator implements IComponent
{
    private $component;

    public function __construct(IComponent $component)
    {
        $this->component = $component;
    }

    public function operation()
    {
        $this->component->operation();
    }
}
