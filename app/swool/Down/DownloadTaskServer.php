<?php

namespace WPLib\Server;

use download\DownloadLogic;
use Logger;
use notice\NoticeLogic;
use WPLib\Process;

/**
 * Class DownloadTaskServer
 * @package WPLib\Server
 * @author wyb
 * @date 2020-04-08 15:20
 */
class DownloadTaskServer
{
    public $di;
    static public $instance;

    public function __construct($params)
    {
        $this->di = \Phalcon\Di::getDefault();

        if (isset($params['config']['log_file'])) {
            $log_path = dirname($params['config']['log_file']);
            if (!file_exists($log_path)) {
                mkdir($log_path, 0755, true);
            }
        }

        //创建对象，监听端口
        $server = new \swoole_server($params['ip'], $params['port']);

        //设置参数
        $server->set($params['config']);

        //监听启动事件
        $server->on('start', [$this, 'onStart']);

        //监听连接进入事件
        $server->on('connect', [$this, 'onConnect']);

        //监听数据接收事件（投递异步任务）
        $server->on('receive', [$this, 'onReceive']);

        //监听连接关闭事件
        $server->on('close', [$this, 'onClose']);

        //处理异步任务
        $server->on('task', [$this, 'onTask']);

        //异步执行结果
        $server->on('finish', [$this, 'onFinish']);

        $server->on('pipeMessage', array($this, 'onPipeMessage'));

        $server->on('workerStart', array($this, 'onWorkerStart'));

        $server->on('managerStart', array($this, 'onManagerStart'));

        //导出数据
        $server->addProcess(Process\DownloadProcess::export($server, $this->di));
        //自动修复状态
        $server->addProcess(Process\DownloadProcess::autoFix($server, $this->di));
        //启动
        $server->start();
    }

    /**
     * onStart
     * @param $server
     * @author isGu
     * @date 2019-04-25 10:09
     */
    public function onStart($server)
    {
        global $argv;

        $process_name = 'swoole ' . $argv[0] . ' [master download]' . ' (' . ENVIRON1 . ')';
        if (function_exists('cli_set_process_title')) {
            @cli_set_process_title($process_name);
        } else if (function_exists('swoole_set_process_name')) {
            swoole_set_process_name($process_name);
        }
        Logger::info('服务已启动' . PHP_EOL);
    }

    /**
     * 管理进程开始
     *
     * @param \swoole_server $server
     */
    public function onManagerStart(\swoole_server $server)
    {
        global $argv;
        cli_set_process_title("swoole {$argv[0]} [manager download]" . ' (' . ENVIRON1 . ')');
        Logger::info("管理进程已启动...\n");
        [0];
    }

    /**
     *
     * Worker线程开始事件
     *
     * @param swoole_server $server
     * @param $worker_id
     */
    public function onWorkerStart(\swoole_server $server, $worker_id)
    {
        global $argv;
        if ($worker_id >= $server->setting['worker_num']) {
            cli_set_process_title("swoole {$argv[0]} [tasker download]" . ' (' . ENVIRON1 . ')');
        } else {
            cli_set_process_title("swoole {$argv[0]} [worker download]" . ' (' . ENVIRON1 . ')');
        }
        echo sprintf("worker start(%s - %s)...\n", posix_getpid(), $worker_id);

        // worker进程重启时将将所有生成中的任务,修改为待生成
        if ($server->taskworker === false) {
            $server->task(['type' => \Constant::DOWNLOAD_SERVER_START]);
        }
    }

    /**
     * onConnect
     * @param $server
     * @param $fd
     * @param $from_id
     * @author isGu
     * @date 2019-04-25 10:09
     */
    public function onConnect($server, $fd, $from_id)
    {
        echo 'Connect success to' . $fd . PHP_EOL;
    }

    /**
     * onReceive
     * @param $server
     * @param $fd
     * @param $from_id
     * @param $data
     * @author isGu
     * @date 2019-04-25 10:12
     */
    public function onReceive($server, $fd, $from_id, $data)
    {
        echo 'Join Task From ' . $fd . ' data:' . $data . PHP_EOL;
        $task_id = $server->task($data);
    }

    /**
     * onClose
     * @param $server
     * @param $fd
     * @param $from_id
     * @author isGu
     * @date 2019-04-25 10:13
     */
    public function onClose($server, $fd, $from_id)
    {
        echo 'Client ' . $fd . ' close connection' . PHP_EOL;
    }

    /**
     * onTask
     * @param $server
     * @param $task_id
     * @param $from_id
     * @param $data
     * @author isGu
     * @date 2019-04-25 10:18
     */
    public function onTask($server, $task_id, $from_id, $data)
    {
        $start_time = microtime(true);
        switch ($data['type']) {
            //执行导出任务
            case \Constant::DOWNLOAD_TYPE_TASK:
                $push_data = unserialize($data['data']);
                if ($push_data) {
                    $download_id = $push_data['download_id'];
                    self::logInfo(sprintf(
                        '[Task Start] [task_id=%s download_id=%s] 开始执行...',
                        $task_id,
                        $download_id
                    ));

                    //保持数据库连接不超时
                    $download_model = new \AdminDownloadRecordModel();
                    $download_model->ensureConnection($download_model::MODE_CONNECTION_ALL);

                    $file_path = (new DownloadLogic())->doExport($download_id);
                    self::logInfo(sprintf(
                        '[Task File] [task_id=%s download_id=%s] 文件路径:%s',
                        $task_id,
                        $download_id,
                        $file_path
                    ));

                    //将临时文件上传至腾讯云
                    if (!empty($file_path)) {
                        $upload = \UploadFile::fileToPath($file_path, 'public', 'download');
                        if ($upload == false) {
                            $msg = \Helper::getLastMessage();
                            self::logInfo(sprintf(
                                '[Task Upload] [task_id=%s download_id=%s] 上传失败:%s',
                                $task_id,
                                $download_id,
                                $msg->getMessage()
                            ));
                        }
                        $file_path = !empty($upload) ? $upload['domain'] . $upload['filename'] : false;
                    }

                    //发送告警 - 如果file_path为空
                    if ($file_path === false && DownloadLogic::$errorMsg === null) {
                        //未知原因导出失败-发送告警信息
                        \Notice::send(\Constant::DOWNLOAD_TYPE_NOTICE, 1, '数据导出失败啦', 0,
                            [
                                ['type' => 1, 'name' => '任务ID', 'val' => $push_data['download_id']],
                                ['type' => 1, 'name' => 'ENVIRON', 'val' => ENVIRON1],
                            ]
                        );
                    }

                    //更新任务状态
                    DownloadLogic::updateExportStatus($download_id, $file_path);
                    $use_time = microtime(true) - $start_time;
                    $server->finish(sprintf(
                            "[Task Finish] [task_id=%s download_id=%s] 导出结果:%s,开始时间:%s,使用时间:%s",
                            $task_id,
                            $download_id,
                            empty($file_path) ? 'FIAL' : 'SUCCESS',
                            date('Y-m-d H:i:s', $start_time),
                            $use_time
                        )
                    );
                }
                break;
            //重置任务状态
            case \Constant::DOWNLOAD_SERVER_START:
                DownloadLogic::reSetStatus();
                break;
            default:
                echo '------------ 未知类型 ------------' . PHP_EOL;
        }
    }

    /**'
     * onFinish
     * @param $server
     * @param $task_id
     * @param $data
     * @author isGu
     * @date 2019-04-25 10:19
     */
    public function onFinish($server, $task_id, $data)
    {
        self::logInfo($data);
    }

    /**
     * pipeMessage 处理线程回调
     * @param $server
     * @param $from_worker_id
     * @param $message
     * @author isGu
     * @date 2019-04-25 11:33
     */
    public function onPipeMessage($server, $from_worker_id, $message)
    {
        self::logInfo("====================================");

        //判断日志文件是否存在,不存在则创建
        $log_path = DATA_PATH . 'logs/' . APP_NAME . '_' . APP_VERSION;
        $log_file = $log_path . '/swoole_download.log';
        if (!file_exists($log_file)) {
            @mkdir($log_path, 0755, true);
            self::logInfo("未获取到日志文件,请重启下载中心服务  ...");
            //发送告警
            \Notice::send(\Constant::DOWNLOAD_TYPE_NOTICE, 1, '重启服务提醒', 0,
                [
                    ['type' => 1, 'name' => '说明', 'val' => '未获取到日志文件,请重启下载中心服务']
                    , ['type' => 1, 'name' => '环境', 'val' => $_SERVER['ENVIRON']]
                ]
            );
            $server->reload();
        }

        //判断tmp文件夹是否存在,不存在则创建
        $tmp_path = BASE_PATH . 'data/tmp/';
        if (!file_exists($tmp_path)) {
            @mkdir($tmp_path, 0755, true);
            self::logInfo("重建tmp文件 ...");
        }

        $params = json_decode($message, true);
        self::logInfo("收到一个新消息 ...");
        switch ($params['type']) {
            case \Constant::DOWNLOAD_TYPE_TASK:
                $task_id = $server->task($params);
                self::logInfo("分发异步任务 task_id={$task_id} ...");
                break;

            case \Constant::DOWNLOAD_TYPE_RELOAD:
                $server->reload();
                break;

            case \Constant::DOWNLOAD_TYPE_SHUTDOWN:
                $server->shutdown();
                break;

            default:
                echo date('Y-m-d H:i:s'), " 收到一个未知任务\n";
                break;
        }
    }

    public static function logInfo($msg)
    {
        echo date('Y-m-d H:i:s') . ' ' . $msg . PHP_EOL;
    }

    /**
     * run
     * @param array $params
     * @return MailTaskServer
     * @author isGu
     * @date 2019-08-02 17:18
     */
    static public function run($params = [])
    {
        if (!self::$instance) {
            self::$instance = new self($params);
        }
        return self::$instance;
    }
}
