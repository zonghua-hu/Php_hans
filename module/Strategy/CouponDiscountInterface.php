<?php

namespace Strategy;

interface CouponDiscountInterface
{
    public function discountAmount(string $coupon, float $money): float;
}
