<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/21
 * Time: 10:33
 */

class DeductionContext
{
    private $obj_deduction;

    public function __construct(IDeduction $deduction)
    {
        $this->obj_deduction = $deduction;
    }

    public function exec(Card $card,Trade $trade)
    {
        return $this->obj_deduction->exec($card,$trade);
    }

}