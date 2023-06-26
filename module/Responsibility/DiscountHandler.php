<?php

namespace Responsibility;

use http\Client\Request;

/**
 * Class DiscountHandler
 * @package Responsibility
 */
class DiscountHandler extends Handler
{
    /**
     * @param Request $request
     * @return mixed|void
     * @throws \Exception
     */
    protected function check(Request $request)
    {
        if (empty($request->discount_rules_data)) {
            throw new \Exception("discount_rules_data error");
        }
    }
}