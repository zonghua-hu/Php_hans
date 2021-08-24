<?php


namespace Decorator;


class ConcreteDecorationTwo extends Decorator
{
    public function __construct(IComponent $component)
    {
        parent::__construct($component);
    }

    public function operation()
    {
        parent::operation();
        $this->echoHansTwo();
    }

    private function echoHansTwo()
    {
        echo "hans two";
    }

}