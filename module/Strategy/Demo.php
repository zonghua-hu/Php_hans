<?php

namespace Strategy;

class Demo
{
    /**
     * 工厂+策略=计算优惠金额的if问题
     * @param string $coupon
     * @param float $orderMoney
     * @param int $type
     * @return float
     */
    public function main(string $coupon, float $orderMoney, int $type)
    {
        $discountObject = DiscountFactory::getObject($type);
        return (new Context())->setDiscount($discountObject)->getDiscount($coupon, $orderMoney);
    }
}
