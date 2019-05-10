<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/10
 * Time: 15:15
 */

abstract class Corp
{
    private $obj;

    public function __construct(ProductBridge $productBridge)
    {
        $this->obj = $productBridge;
    }

    public function makeMoney()
    {
        $this->obj->beProducted();
        $this->obj->beSelled();

    }


}