<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/29
 * Time: 21:02
 */

use Swoole\Http\Server;

$http = new Server("0.0.0.0", 9501);

//2.设置运行时参数
$http->set(array(
    'worker_num' => 8,
    'daemonize' => 0,
    'max_request' => 10000,
    'dispatch_mode' => 2,
    'debug_mode'=> 1,
    'document_root' => '/sites/Php_hans/public',
    'enable_static_handler' => true,
));

//3.注册事件回调函数
$http->on('request', function(swoole_http_request $request, swoole_http_response $response) {
    $response->end("<h1>hello swoole</h1>");
});

//启动
$http->start();