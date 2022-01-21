<?php

namespace bridge;

class PlainFormat implements FormatterInterface
{
    public function format(string $forString)
    {
        return str_replace(' ', ',', $forString);
    }
}
