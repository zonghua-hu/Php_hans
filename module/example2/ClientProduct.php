<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/29
 * Time: 11:34
 */

use ConcreteProduct;

class ClientProduct
{
    public static function main($data)
    {
        $prod_obj = new ConreteCreate($data);
        return $prod_obj->create();
    }
}