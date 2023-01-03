<?php

namespace App\Services\Entrust;

use App\Dto\Sms\SmsContentDto;

/**
 * 委托约束
 * Interface InterfaceEntrust
 * @package App\Services\Entrust
 */
interface InterfaceEntrust
{
    public function send(SmsContentDto $contentDto);
}
