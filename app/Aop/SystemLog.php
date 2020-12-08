<?php

class SystemLog
{
    private $error;

    public function __construct(ErrorException $errorException)
    {
        $this->error = $errorException;
    }

    public function beginLog()
    {
        $errCode = $this->error->getCode() ?? 200;
        $errMsg = $this->error->getMessage() ?? 'succeed';
        echo "before===错误代码:" . $errCode . PHP_EOL . "错误信息:" . $errMsg;
    }

    public function afterLog()
    {
        $errCode = $this->error->getCode() ?? 200;
        $errMsg = $this->error->getMessage() ?? 'succeed';
        echo "after===错误代码:" . $errCode . PHP_EOL . "错误信息:" . $errMsg;
    }
}
