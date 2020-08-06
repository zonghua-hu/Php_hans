<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

date_default_timezone_set('Asia/Shanghai');

include 'server/SwooleTcp.php';



$options = getopt('type:');

$version = 1.0;
$arguments = [];
$ip = '127.0.0.1';
$port = 9501;
$serverType = 'tcp';
foreach ($options as $key => $value) {
    switch ($key) {
        case 'ip':
            $ip = $value;
            break;
        case 'port':
            $port = $value;
            break;
        case 'type':
            $serverType = $value;
            break;
    }
}
$arguments['ip'] = $ip;
$arguments['port'] = $port;
$arguments['config'] = ['worker_num' => 1, 'task_worker_num' => 1];


switch ($serverType) {
    case 'tcp':
        \swool\SwooleTcp::run($arguments);
        break;
    case 'http':
        \swool\SwooleHttp::run($arguments);
        break;
    case 'webSocket':
        \swool\SwooleWebSocket::run($arguments);
        break;
}
