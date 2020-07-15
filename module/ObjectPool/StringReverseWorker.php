<?php

namespace ObjectPool;

class StringReverseWorker
{
    private $createAt;

    public function __construct()
    {
        $this->createAt = new \DateTime();
    }

    public function run(String $text)
    {
        return strrev($text);
    }

}