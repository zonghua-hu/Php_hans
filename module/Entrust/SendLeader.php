<?php

namespace App\Services\Entrust;

use App\Dto\Sms\SmsContentDto;

/**
 * 委托者
 * Class SendLeader
 * @package App\Services\Entrust
 */
class SendLeader
{
    private $worker;

    /**
     * @param InterfaceEntrust $interfaceEntrust
     * @return $this
     */
    public function setObj(InterfaceEntrust $interfaceEntrust)
    {
        $this->worker = $interfaceEntrust;
        return $this;
    }

    /**
     * @param SmsContentDto $contentDto
     * @return mixed
     */
    public function handle(SmsContentDto $contentDto)
    {
        return $this->worker->send($contentDto);
    }
}
