<?php

namespace Strategy;

class PriceDiscountAmount implements CouponDiscountInterface
{
    /**
     * @param string $coupon
     * @param float $money
     * @return float
     */
    public function discountAmount(string $coupon, float $money): float
    {
        return $money > 1000 ? $money - 100 : $money - (int)$coupon;
    }
}
