<?php

namespace Strategy;

/**
 * Class Context
 * @package Strategy
 */
class Context
{
    public CouponDiscountInterface $discount;

    /**
     * @param CouponDiscountInterface $couponDiscount
     * @return $this
     */
    public function setDiscount(CouponDiscountInterface $couponDiscount): Context
    {
        $this->discount = $couponDiscount;
        return $this;
    }

    /**
     * 获取最终折扣金额
     * @param string $coupon
     * @param float $money
     * @return float
     */
    public function getDiscount(string $coupon, float $money)
    {
        return $this->discount->discountAmount($coupon, $money);
    }
}
