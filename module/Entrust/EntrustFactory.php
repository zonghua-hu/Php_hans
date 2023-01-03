<?php

namespace App\Services\Entrust;

use App\Enums\AutoTrigger\AutoTriggerHandleEnum;
use App\Lib\BaseFactory;
use App\Lib\BaseFactoryTrait;

/**
 * 委托工厂
 * Class EntrustFactory
 * @package App\Services\Entrust
 */
class EntrustFactory extends BaseFactory
{
    use BaseFactoryTrait;

    protected static $objects = [];

    /**
     * 折扣规则映射
     * @var string[]
     */
    protected static $map = [
        AutoTriggerHandleEnum::SMS_HANDLE   => SendSms::class,
        AutoTriggerHandleEnum::EMAIL_HANDLE => SendEmail::class,
    ];
}
