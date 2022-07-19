<?php

namespace factory;

use BaseTrait\BaseFactoryTrait;

class ExampleFactory extends BaseFactory
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
        1 => NoApiSubmitRemovalInbound::class,
        2 => ApiSubmitRemovalInbound::class
    ];
}
