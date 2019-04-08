<?php

namespace Swoole\Coroutine\Component\Hook;

use Swoole\Coroutine\Component\Base;
use Swoole\Component\Redis as CoRedis;

class Redis extends Base
{
    protected $type = 'redis';

    function __construct($config)
    {
        parent::__construct($config);
        \Swoole::getInstance()->beforeAction([$this, '_createObject'],\Swoole::coroModuleRedis);
        \Swoole::getInstance()->afterAction([$this, '_freeObject'],\Swoole::coroModuleRedis);
    }


    function create()
    {
        return new CoRedis($this->config);
    }

    /**
     * 调用$driver的自带方法
     * @param $method
     * @param array $args
     * @return mixed
     */
    function __call($method, $args = array())
    {
        $redis = $this->_getObject();
        if (!$redis)
        {
            return false;
        }
        return $redis->{$method}(...$args);
    }
}
