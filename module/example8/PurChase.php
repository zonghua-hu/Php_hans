<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/13
 * Time: 9:59
 */

class PurChase
{
    private $stock;

    private $sale;

    public function __construct()
    {
        $this->stock = new Stock();

        $this->sale = new Sale();

    }

    public function refuseBuyIBM()
    {
        echo "不要在采购IBM的电脑了";
    }

    public function buyIbmComputer($number)
    {
        $stock = $this->sale->getSaleStatus();
        if ($stock > 80) {
            echo "采购IBM电脑".$number."台";
            $this->stock->increase($number);
        } else {
            echo "销量不好，采购".($number/2)."台";
        }
    }


}