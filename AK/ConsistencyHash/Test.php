<?php

require_once 'ConsistentHash.php';
require_once 'MyConsistentHash.php';

$hashServer = new \ConsistencyHash\MyConsistentHash();
$ip = [
    '192.168.1.1',
    '192.168.1.2',
    '192.168.1.3',
    '192.168.1.4',
    '192.168.1.5',
    '192.168.1.6',
    '192.168.1.7',
    '192.168.1.8',
    '192.168.1.9',
    '192.168.1.10',
];
list($succeedNum, $failServer) = $hashServer->initServer($ip);
if ($succeedNum != count($ip)) {
    //报警
    echo "指定机器添加失败" . json_encode($failServer);
}
//开始保存数据
for ($i = 0; $i < 10; $i++) {
    $dataKey = "key" . $i;
    echo "KEY：" . $dataKey . "保存到" . $hashServer->searchKey($dataKey) . PHP_EOL;
}
//移除机器
$hashServer->removeServer('192.168.1.2');
for ($i = 0; $i < 10; $i++) {
    $dataKey = "key" . $i;
    echo "移除后=KEY：" . $dataKey . "保存到" . $hashServer->searchKey($dataKey) . PHP_EOL;
}
//增加机器
$hashServer->addServer('192.168.1.12');
for ($i = 0; $i < 10; $i++) {
    $dataKey = "key" . $i;
    echo "增加后=KEY：" . $dataKey . "保存到" . $hashServer->searchKey($dataKey) . PHP_EOL;
}
