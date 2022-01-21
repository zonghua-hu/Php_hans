<?php

namespace bridge;

class HttpFormat implements FormatterInterface
{
    public function format(string $forString)
    {
        return sprintf('<p>%s</p>', $forString);
    }
}
