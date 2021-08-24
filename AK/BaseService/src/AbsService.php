<?php

namespace BaseService\src;

abstract class AbsService
{
    const SUCCESS_CODE = 200;

    protected $timeOutSec = 3;

    protected $requestBuilder;

    public function __construct()
    {
        $this->requestBuilder = null;
        $envCode = 'sk-service.' . $this->getLbServiceEnvCode();
        $this->requestBuilder->setServiceUrl();
        $this->requestBuilder->setParameter();
        $this->requestBuilder->setTimeOut($this->timeOutSec);
        if ($this->enableSign()) {
            $this->requestBuilder->withSign();
        }
    }

    public function setTimeOutSec($sec)
    {
        $this->timeOutSec = $sec;
    }

    protected function getSuccessCode()
    {
        return self::SUCCESS_CODE;
    }

    protected function enableSign()
    {
        return FrameProvider::getEnvInstance()->get('ak.enable_sign', false);
    }

    protected function responseData($responseContent, $dto = null)
    {
        $result = $responseContent->getBody()->toArray();
        if ($result['code'] != $this->getSuccessCode()) {
            throw new BaseServiceException($result['code'], $result['message']);
        }
        if ($dto !== null) {
            $dto->fillObject($result['data'] ?? []);
            return $dto;
        }
        return $result['data'] ?? '';
    }

}