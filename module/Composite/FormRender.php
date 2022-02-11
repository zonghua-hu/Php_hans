<?php

namespace Composite;

class FormRender implements RenderInterface
{
    private $elements = [];

    public function render()
    {
        $formCode = '<form>';
        foreach ($this->elements as $value) {
            $formCode .= $value->render();
        }
        $formCode .= '</form>';
        return $formCode;
    }

    public function addElements(RenderInterface $render)
    {
        $this->elements[] = $render;
    }
}
