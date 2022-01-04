<?php

require_once 'DelayQueueRedis.php';

$queue = "closeOrderRedisQueue";
$config = [
    'host' => 'redis32',
    'port' => 6379,
    'auth' => 'rd_local@2017',
    'timeout' => 60
];
$queue = new \app\Algorithm\DelayQueueRedis($queue, $config);

$queue->addTask('order1', time() + 30, ['order_id' => 111]);
$queue->addTask('order2', time() + 30, ['order_id' => 222]);
//$queue->addTask('order1', time() + 30, ['order_id' => 111]);
//$queue->addTask('order1', time() + 30, ['order_id' => 111]);