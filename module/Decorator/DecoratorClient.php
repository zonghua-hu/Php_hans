<?php

namespace Decorator;

class DecoratorClient
{
    public function main()
    {
        $component = new ConcreteComponent();
        $one = new ConcreteDecorationOne($component);
        $one->operation();
    }
}
