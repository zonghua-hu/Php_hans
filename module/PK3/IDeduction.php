<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/21
 * Time: 10:32
 */

interface IDeduction
{
    public function exec(Card $card,Trade $trade);
}