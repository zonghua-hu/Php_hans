<?php

namespace Responsibility;

use http\Client\Request;

/**
 * Class AccountMoneyHandler
 * @package Responsibility
 */
class AccountMoneyHandler extends Handler
{
    /**
     * @param Request $request
     * @return mixed|void
     * @throws \Exception
     */
    protected function check(Request $request)
    {
        if (empty($request->money)) {
            throw new \Exception("money is empty");
        }
    }
}
