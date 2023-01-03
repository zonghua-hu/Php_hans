<?php

namespace App\Services\Entrust;

use App\Dto\Sms\SmsContentDto;

/**
 * 委托模式demo
 * Class EntrustDemo
 * @package App\Services\Entrust
 */
class EntrustDemo
{
    public function main()
    {
        $smsDto = SmsContentDto::fromItem(['shopSign' => getSign()]);
        $config = config('autoTrigger.auto');
        $leader = new SendLeader();
        foreach ($config as $handleType) {
            $handle = EntrustFactory::getObject($handleType);
            $leader->setObj($handle)->handle($smsDto);
        }
    }
}
