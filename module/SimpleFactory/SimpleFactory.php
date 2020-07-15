<?php

namespace SimpleFactory;

class SimpleFactory
{
    public function createBicycle():Bicycle
    {
        return new Bicycle();
    }
}
