<?php

namespace swool;

use Swoole\Server;

class SwooleTcp
{
    //查看进程： ps -ef | grep "server"

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

        //使用message需要注册此函数
        $server->on('pipeMessage', [$this, 'onPipeMessage']);

        $server->on('workerStart', array($this, 'onWorkerStart'));

        //创建自定义进程
//        $server->addProcess($this->addProcessNew($server));

        //启动服务器
        $server->start();
    }

    /**
     * @Notes:启动worker进程
     * @param $server
     * @param $worker_id
     * @User: Hans
     * @Date: 2020/8/10
     * @Time: 8:21 下午
     */
    public function onWorkerStart($server, $worker_id)
    {
        global $argv;
        if ($worker_id >= $server->setting['worker_num']) {
            cli_set_process_title("swoole {$argv[0]} [tasker download]");
        } else {
            cli_set_process_title("swoole {$argv[0]} [worker download]");
        }
//        echo sprintf("worker start(%s - %s)...\n", posix_getpid(), $worker_id);
    }

    /**
     * @Notes:sendMessage方法触发的函数，在worker和task中都可能会触发。
     * @param $server
     * @param $taskId
     * @param $message
     * @User: Hans
     * @Date: 2020/8/10
     * @Time: 7:22 下午
     */
    public function onPipeMessage($server, $taskId, $message)
    {
        echo __METHOD__.":";
        $this->echoPid($server);
        echo '收到信息1'.$message;
        if (empty($message) || !$message) {
            echo "进程process处理异步任务失败，进程id:".$taskId;
        } else {
            if (strpos($message, '2')) {
                $Id = $server->task($message);
                echo "当前taskId:".$Id.PHP_EOL;
            }
        }
    }

    /**
     * @Notes:输出进程id
     * @param $server
     * @User: Hans
     * @Date: 2020/8/10
     * @Time: 8:21 下午
     */
    public function echoPid($server)
    {
        echo PHP_EOL;
        echo "master进程id：".$server->master_pid.PHP_EOL;
        echo "manager进程id:".$server->manager_pid.PHP_EOL;
        echo "worker进程id:".$server->worker_pid.PHP_EOL;

        echo "worker进程编号:".$server->worker_id.PHP_EOL;
        echo "当前是否为task进程".var_dump($server->taskworker).PHP_EOL;
        echo PHP_EOL;
    }

    /**
     * @Notes:task任务完成通知方法
     * 在worker进程中执行的方法
     * @param $server
     * @param $taskId
     * @param $data
     * @User: Hans
     * @Date: 2020/8/10
     * @Time: 2:14 下午
     */
    public function onFinish($server, $taskId, $data)
    {
        echo __METHOD__.":";
        $this->echoPid($server);
        echo "【{$taskId}】"."finish task data is: ".$data;
    }

    /**
     * @Notes:异步任务投递task
     * 在task进程种执行的方法
     * @param $server
     * @param $taskId
     * @param $fromId
     * @param $data
     * @User: Hans
     * @Date: 2020/8/10
     * @Time: 2:14 下午
     */
    public function onTask($server, $taskId, $fromId, $data)
    {
        echo __METHOD__.":";
        $this->echoPid($server);
        if (strpos($data, '2')) {
            $data = "来自自定义进程的消息处理完毕，即将返回worker进程";
        } else {
            $data = '收到来自：'.$fromId."投递的任务：".$taskId."参数：". $data;
        }
        sleep(2);
        $server->finish($data);
    }

    /**
     * @Notes:接受消息
     * @param $server
     * @param $fd
     * @param $from_id
     * @param $data
     * @User: Hans
     * @Date: 2020/8/10
     * @Time: 2:17 下午
     */
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
                $server->task($data, -1);
                break;
            case 'tick':
                $server->tick(5000, function () use ($server, $fd) {
                    $server->send($fd, "hello swoole.\n");
                });
                break;
        }
    }

    /**
     * @Notes:添加自定义进程
     * @param $server
     * @return \swoole_process
     * @User: Hans
     * @Date: 2020/8/10
     * @Time: 5:52 下午
     */
    public function addProcessNew($server)
    {
        return new \swoole_process(function (\swoole_process $process) use ($server) {
            $i = 0;
            while (true) {
                $i++;
                if ($i == 5) {
                    break;
                }
                global $argv;
                $workerId = $server->setting['worker_num'] - 1;
                $num = 100;
                $str = "【投递任务-自定义进程】---【{$workerId}】";
                for ($i = 0; $i<= $num; $i++) {
                    $str .= $i.',';
                }
                $str = trim($str, ',');
                sleep(5);
                echo $str.PHP_EOL;
                $server->sendMessage($str, $workerId);
            }
        }, false, 2, 1);
    }

    /**
     * @Notes:关闭客户端连接
     * @param $server
     * @param $fd
     * @User: Hans
     * @Date: 2020/8/10
     * @Time: 8:20 下午
     */
    public function onClose($server, $fd)
    {
        echo "Client: Close.\n";
    }

    /**
     * @Notes:链接函数
     * @param $server
     * @param $fd
     * @User: Hans
     * @Date: 2020/8/10
     * @Time: 8:20 下午
     */
    public function onConnect($server, $fd)
    {
        $client = $server->getClientInfo($fd);

        echo "from ".$client['remote_ip']." Client: Connect succeed.\n";
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
