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

$queue->addTask('order1', time() + 3, ['order_id' => 111]);
sleep(5);
$queue->addTask('order2', time() + 5, ['order_id' => 222]);
sleep(5);
$queue->addTask('order3', time() + 7, ['order_id' => 333]);
$queue->addTask('order3', time() + 700, ['order_id' => 333]);

while (true) {
    $queue->run();
    usleep(100000);
}
