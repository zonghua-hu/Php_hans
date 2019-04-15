<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 10:21
 */

class Mediator extends AbstractMediator
{
    public function execute($str,$obj)
    {
        if ($str == "purchase.buy") {
            
        }


    }

    public function buyComputer($number)
    {
        $sale_status = $this->sale.getSaleStatus();
        
    }

}