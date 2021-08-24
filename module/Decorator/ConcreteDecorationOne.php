<?php

namespace Decorator;

class ConcreteDecorationOne extends Decorator
{
    public function __construct(IComponent $component)
    {
        parent::__construct($component);
    }
    public function operation()
    {
        parent::operation();
        $this->echoHansOne();
    }

    private function echoHansOne()
    {
        echo "hans one";
    }
}
