<?php

namespace Strategy;

class ZeroDiscountAmount implements CouponDiscountInterface
{
    /**
     * @param string $coupon
     * @param float $money
     * @return float
     */
    public function discountAmount(string $coupon, float $money): float
    {
        return $money == 88.88 ? 0 : $money - (int)$coupon;
    }
}
