#!/usr/bin/env php
<?php
use Phalcon\CLI\Console as ConsoleApp;

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

date_default_timezone_set('Asia/Shanghai');

// $debug = new \Phalcon\Debug();
// $debug->listen();

try {
    // 应用名称
    define('APP_NAME', 'app_merchant_download');
    define('IN_CLI', true);

    /**
     * 环境 - ENVIRON
     * 可选值：production, preview, develop, test
     */

    // 参数处理
    $options = getopt('n:m:c:a:', ['ENVIRON:', 'VERSION:', 'DAEMONIZE:','ENVIRON1:']);

    // 默认版本号
    $app_version = "v1.0";
    $arguments   = [];
    $arguments['server_id'] = 1;
    foreach ($options as $k => $v) {
        switch ($k) {
            case 'n':
                break;

            case 'm':
                break;

            case 'c':
                $arguments['task']   = $v;
                break;

            case 'a':
                $arguments['action'] = $v;
                break;

            case 'DAEMONIZE':
                $arguments['daemonize'] = $v === '0' ? false : true;
                break;

            case 'ENVIRON':
                $_SERVER['ENVIRON']  = $v;
                break;

            case 'VERSION':
                $app_version = $v;
                break;

            case 'SERVER_ID':
                $arguments['server_id'] = $v;
                break;

            case 'WORKER_NUM':
                $arguments['worker_num']  = $v;
                break;

            case 'TASK_WORKER_NUM':
                $arguments['task_worker_num']  = $v;
                break;

            case 'ENVIRON1':
                $_SERVER['ENVIRON1']  = $v;
                break;

            default:
                $arguments[] = $v;
        }
    }

    if ($arguments['server_id']) {
        define('SERVER_ID', $arguments['server_id']);
    }
    // 其他参数
    $params = $_SERVER['argv'];
    unset($params[0]);
    foreach ($params as $k => $v) {
        if ($v[0] == '-') {
            continue;
        }
        @list ($name, $value) = explode('=', $v);
        $arguments['params'][$name] = $value;
    }
    unset($params);

    if (isset($_SERVER['ENVIRON'])) {
        define('ENVIRON', $_SERVER['ENVIRON']);
    } else {
        die ("请指定运行环境 --ENVIRON！\n");
    }

    define("ENVIRON1",$_SERVER['ENVIRON1'] ?? ENVIRON);

    define("APP_VERSION", $app_version);

    define("DS", DIRECTORY_SEPARATOR);

    define("APP_PATH", dirname(__DIR__) . DS . APP_VERSION . DS);

    define("BASE_PATH", dirname(dirname(APP_PATH)) . DS);

    define("LIBRARY_PATH", BASE_PATH . "library" . DS);

    define("DATA_PATH", BASE_PATH . "data" . DS);

    /**
     * Application Vendor Autoload
     */
    include APP_PATH . 'vendor/autoload.php';

    /**
     * Library Vendor Autoload
     */
    include LIBRARY_PATH . 'vendor/autoload.php';

    /**
     * Read the configuration
     */
    $config = include APP_PATH . "config/config.php";

    /**
     * 自动加载
     */
    include APP_PATH . "config/loader.php";

    /**
     * Read services
     */
    include APP_PATH . "config/services.php";

    /**
     * Handle the request
     */
    $application = new \WPLib\Console\Application($di);

    $di->set('application', $application, true);

    $port = 9083;

    switch (ENVIRON) {
        case 'production_blue':
            $daemonize       = isset($arguments['daemonize']) ? $arguments['daemonize'] : true;
            $worker_num      = isset($arguments['worker_num']) ? $arguments['worker_num'] : 1;
            $task_worker_num = isset($arguments['task_worker_num']) ? $arguments['task_worker_num'] : 2;
            break;

        case 'production_green':
            $daemonize       = isset($arguments['daemonize']) ? $arguments['daemonize'] : true;
            $worker_num      = isset($arguments['worker_num']) ? $arguments['worker_num'] : 1;
            $task_worker_num = isset($arguments['task_worker_num']) ? $arguments['task_worker_num'] : 2;
            break;

        case 'production':
            $daemonize       = isset($arguments['daemonize']) ? $arguments['daemonize'] : true;
            $worker_num      = isset($arguments['worker_num']) ? $arguments['worker_num'] : 1;
            $task_worker_num = isset($arguments['task_worker_num']) ? $arguments['task_worker_num'] : 2;
            break;

        case 'preview':
            $daemonize       = isset($arguments['daemonize']) ? $arguments['daemonize'] : true;
            $worker_num      = isset($arguments['worker_num']) ? $arguments['worker_num'] : 1;
            $task_worker_num = isset($arguments['task_worker_num']) ? $arguments['task_worker_num'] : 2;
            break;

        case 'test':
            $daemonize       = isset($arguments['daemonize']) ? $arguments['daemonize'] : true;
            $worker_num      = isset($arguments['worker_num']) ? $arguments['worker_num'] : 1;
            $task_worker_num = isset($arguments['task_worker_num']) ? $arguments['task_worker_num'] : 2;
            break;

        case 'develop':
        default:
            $daemonize       = isset($arguments['daemonize']) ? $arguments['daemonize'] : true;
            $worker_num      = isset($arguments['worker_num']) ? $arguments['worker_num'] : 1;
            $task_worker_num = isset($arguments['task_worker_num']) ? $arguments['task_worker_num'] : 2;
            break;
    }

    $message_queue_key = '0x72109522';

    //根据环境改变端口号
    if(ENVIRON1 != 'production'){
        $environ_str = substr(ENVIRON1,-1);
        if(is_numeric($environ_str) && in_array($environ_str,[1,2,3,4,5])){
            //端口号调整 test: 9083 + 1 + 100 = 9184
            $port = $port + $environ_str + 100;
            $message_queue_key .= $environ_str;
        }
    }

    $arguments = [
        'ip'        => '127.0.0.1',
        'port'      => $port,
        'config'    => [
            'dispatch_mode'         => 2, // 非抢占模式
            'task_ipc_mode'         => 2, // 消息队列通讯,非争抢模式
            'daemonize'             => $daemonize, //1守护进程
            'worker_num'            => $worker_num, //进程数, 这里设置为CPU核数的1-4倍最合理
            //'message_queue_key'     => 0x72109522,
            'message_queue_key'     => $message_queue_key,
            'task_worker_num'       => $task_worker_num,
            'backlog'               => 1024,
            'max_request'           => 1024,
            'task_max_request'      => 4,

            'open_length_check'     => true, //包数据监测
            'package_length_type'   => 'n',
            'package_length_offset' => 0,
            'package_body_offset'   => 2,
            'package_max_length'    => 1024 * 1024 * 2,

            'log_file'         => DATA_PATH . 'logs/' . APP_NAME . '_' . APP_VERSION . '/swoole_download.log',
        ]
    ];

    \WPLib\Server\DownloadTaskServer::run($arguments);

} catch (\Exception $e) {
    /**
     * 错误捕获处理
     */
    Logger::begin();
    Logger::error(sprintf("%s[%s]: %s", $e->getFile(), $e->getLine(), $e->getMessage()));
    Logger::error($e->getTraceAsString());
    Logger::commit();

    echo $e->getMessage(), "\n", $e->getTraceAsString(), "\n";
}
