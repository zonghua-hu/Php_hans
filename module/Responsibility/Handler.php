<?php

namespace Responsibility;

use http\Client\Request;

/**
 * Class Handler
 * @package Responsibility
 */
abstract class Handler
{
    /**
     * 检查器对象
     * @var \App\Services\Mail\MailCheckResponsibility\MailHandler $nextHandler
     */
    private Handler $nextHandler;

    /**
     * 执行检查器
     * @param Request $request
     */
    final public function handle(Request $request)
    {
        $this->check($request);
        if (!empty($this->nextHandler)) {
            $this->nextHandler->handle($request);
        }
    }

    /**
     * 设置检查器
     * @param Handler $handler
     * @return Handler
     */
    public function setNext(Handler $handler): Handler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    /**
     * 业务检查
     * @param Request $request
     * @return mixed
     */
    abstract protected function check(Request $request);
}