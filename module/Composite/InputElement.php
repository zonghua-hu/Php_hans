<?php

namespace Composite;

class InputElement implements RenderInterface
{
    public function render()
    {
        return '<input type="text"/>' . PHP_EOL;
    }
}
