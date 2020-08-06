<?php

namespace swool;

use Swoole\Server;

class SwooleTcp
{
    private static $install = null;

    public function __construct($params)
    {
        //创建Server对象，监听 127.0.0.1:9501 端口
        $server = new Server($params['ip'], $params['port']);

        //设置进程参数
        $server->set($params['config']);

        //监听连接进入事件
        $server->on('Connect', [$this,'onConnect']);

        //监听数据接收事件
        $server->on('Receive', [$this,'onReceive']);

        //监听连接关闭事件
        $server->on('Close', [$this,'onClose']);

        //处理异步任务
        $server->on('task', [$this,'onTask']);

        //finsh
        $server->on('finish', [$this, 'onFinish']);


        //启动服务器
        $server->start();
    }

    public function onFinish($server, $taskId, $data)
    {
        echo 'Task Data <'.$data.'>'.'-------------'.$taskId.'finish'.PHP_EOL;
    }

    public function onConnect($server, $fd)
    {
        $client = $server->getClientInfo($fd);

        echo "from ".$client['remote_ip']." Client: Connect succeed.\n";
    }

    public function onTask($server, $taskId, $fromId, $data)
    {
        $startTime = time();
        sleep(5);
        $server->finish('开始时间：'.date('Y:m:d H:i:s', $startTime).'结束时间'.date('Y:m:d H:i:s', time()));
    }

    public function onReceive($server, $fd, $from_id, $data)
    {
        switch (trim($data)) {
            case 'hello':
                $server->send($fd, "receive from ".$from_id."data ".$data." and Server send: " . $data);
                break;
            case 'close':
                $server->close($fd, true);
                break;
            case 'work':
                $server->task($data, 1);
                break;
            case 'tick':
                $server->tick(5000, function () use ($server, $fd) {
                    $server->send($fd, "hello swoole.\n");
                });
                break;
        }
    }

    public function onClose($server, $fd)
    {
        echo "Client: Close.\n";
    }

    /**
     * @Notes:单利启动tcp服务
     * @param array $params
     * @return SwooleTcp|null
     * @User: Hans
     * @Date: 2020/8/6
     * @Time: 12:15 下午
     */
    public static function run($params = [])
    {
        if (! self::$install instanceof self) {
            self::$install = new  self($params);
        }
        return self::$install;
    }

}
