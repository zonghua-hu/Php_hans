<?php

namespace Strategy;

class DefaultDiscountAmount implements CouponDiscountInterface
{
    /**
     * @param string $coupon
     * @param float $money
     * @return float
     */
    public function discountAmount(string $coupon, float $money): float
    {
        return $coupon ? ($money - (int)$coupon) * 100 : $money;
    }
}
