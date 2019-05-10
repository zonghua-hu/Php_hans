<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/10
 * Time: 15:16
 */

class House extends ProductBridge
{
    public function beProducted()
    {
        // TODO: Implement beProducted() method.
        echo "房子是这样生产出来的~";
    }
    public function beSelled()
    {
        // TODO: Implement beSelled() method.
        echo "房子是这样子被卖出去的~";
    }

}