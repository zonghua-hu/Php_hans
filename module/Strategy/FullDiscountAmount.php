<?php

namespace Strategy;

class FullDiscountAmount implements CouponDiscountInterface
{
    /**
     * @param string $coupon
     * @param float $money
     * @return float
     */
    public function discountAmount(string $coupon, float $money): float
    {
        return $money > 100 ? $money - (int)$coupon : $money;
    }
}
