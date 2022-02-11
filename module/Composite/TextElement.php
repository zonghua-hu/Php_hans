<?php

namespace Composite;

class TextElement implements RenderInterface
{
    private $text;

    public function __construct(string $input)
    {
        $this->text = $input;
    }
    public function render()
    {
        return $this->text . ".txt" . PHP_EOL;
    }
}
