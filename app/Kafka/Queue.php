<?php

namespace Kafka;

interface Queue
{
    /**
     * @Notes:向队列添加消息
     * @param $queueName
     * @param $message
     * @return mixed
     * @User: Hans
     * @Date: 2020/12/8
     * @Time: 下午2:26
     */
    public function publish($queueName, $message);

    /**
     * @Notes:消费队列中的数据
     * @param $queueName
     * @param callable $callback
     * @return mixed
     * @User: Hans
     * @Date: 2020/12/8
     * @Time: 下午2:27
     */
    public function consume($queueName, callable $callback);

    /**
     * @Notes:只消费队列数据一次
     * @param $queueName
     * @param callable $callback
     * @return mixed
     * @User: Hans
     * @Date: 2020/12/8
     * @Time: 下午2:28
     */
    public function consumeOnce($queueName, callable $callback);
}
