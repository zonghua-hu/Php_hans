<?php

namespace Strategy;

use BaseTrait\BaseFactoryTrait;
use factory\BaseFactory;

/**
 * 优惠计算工厂
 * Class DiscountFactory
 * @package Strategy
 */
class DiscountFactory extends BaseFactory
{
    use BaseFactoryTrait;

    /**
     * 对象集合
     * @var array
     */
    protected static $objects = [];
    /**
     * 订单提交类型对应的实例
     * @var string[]
     */
    protected static $map = [
        DiscountEnums::DISCOUNT_DEFAULT => DefaultDiscountAmount::class,
        DiscountEnums::DISCOUNT_FULL    => FullDiscountAmount::class,
        DiscountEnums::DISCOUNT_PRICE   => PriceDiscountAmount::class,
        DiscountEnums::DISCOUNT_ZERO    => ZeroDiscountAmount::class,
    ];
}
