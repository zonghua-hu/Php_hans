<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 10:18
 */

abstract class AbstractMediator
{
    public $purchase;
    public $sale;
    public $stock;

    public function __construct()
    {
        $this->stock = new Stock();
        $this->purchase = new PurChase();
        $this->sale = new Sale();
    }

    public abstract function execute($string,$obj);

}