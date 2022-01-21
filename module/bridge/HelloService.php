<?php

namespace bridge;

class HelloService extends Service
{
    public function getFormatter(string $formatString)
    {
        return $this->implementation->format($formatString);
    }
}
