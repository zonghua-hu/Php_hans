<?php

namespace Responsibility;

use http\Client\Request;

class SensitiveWordsHandler extends Handler
{
    protected function check(Request $request)
    {
        return true;
    }
}
