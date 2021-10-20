<?php

namespace Container;

class Person
{
    private $echo;

    public function __construct(Son $son)
    {
        $this->echo = $son->say();
    }

    public function say()
    {
        echo $this->echo;
    }

}