<?php

namespace Decorator;

class ConcreteComponent implements IComponent
{
    public function operation()
    {
        echo '通用基础逻辑';
    }
}
