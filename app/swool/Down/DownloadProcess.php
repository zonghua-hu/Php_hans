<?php
/**
 *
 * @author Root
 * @copyright 2019-2028
 */

namespace WPLib\Process;

use download\DownloadLogic;
use AdminDownloadRecordModel;
use Logger;
use Constant;
use Phalcon\Di;
use swoole_process;
use WPLib\Caching\Redis;

class DownloadProcess
{
    static public function export($server, $di)
    {
        return new swoole_process(function (swoole_process $worker) use ($server, $di) {
            global $argv;

            $process_name = 'swoole ' . $argv[0] . ' [process export download]' . ' (' . ENVIRON1 . ')';
            if (function_exists('cli_set_process_title')) {
                @cli_set_process_title($process_name);
            } else if (function_exists('swoole_set_process_name')) {
                swoole_set_process_name($process_name);
            }

            define('IS_DISABLE_PROFILER', true);

            $is_running = 1;

            declare(ticks=1);

            if (pcntl_signal(SIGTERM, function () use (&$is_running) {
                    Logger::info("set SIGTERM signale handle success!");
                    $is_running = 0;
                }) === false) {
                Logger::error("set SIGTERM signale handle error!");
            }

            Logger::info("数据导出处理程序开始运行...\n");

            try {
                //消费redis stream
                /**
                 * @var $redis Redis
                 */
                $redis = $di->get('redis');

                $stream = DownloadLogic::QUEUE_STREAM;
                $group = DownloadLogic::QUEUE_GROUP;
                $consumer = DownloadLogic::QUEUE_CONSUMER;

                //获取流信息
                $streamInfo = $redis->xInfoStream($stream);
                if (!empty($streamInfo)) {
                    Logger::info(sprintf("redis stream信息 %s \n)", json_encode($streamInfo)));
                    $needCreateStream = false;
                } else {
                    $needCreateStream = true;
                }

                //获取消费组信息
                $groupList = $redis->xInfoGroups($stream);
                $needCreateGroup = true;
                if (!empty($groupList)) {
                    Logger::info(sprintf("redis group信息 %s \n)", json_encode($groupList)));
                    foreach ($groupList as $val) {
                        if ($val['name'] == $group) {
                            $needCreateGroup = false;
                            break;
                        }
                    }
                }

                //创建消费组,如果不存在
                if ($needCreateGroup === true) {
                    $createGroupResult = $redis->xGroupCreate($stream, $group, 0, $needCreateStream);
                    if ($createGroupResult === false) {
                        $err_msg = sprintf("redis 创建消费组失败 stream: %s, group: %s \n", $stream, $group);
                        Logger::error($err_msg);
                        throw new \Exception($err_msg, \WPLib\Constant::COMMON_STATUS);
                    }
                }

                Logger::info(sprintf("redis 消费组信息 stream: %s, group: %s \n", $stream, $group));

                $limit = 100;   //每页读取数量
                $block = 1;     //闲置等待

                while (true) {
                    if ($is_running != 1) {
                        break;
                    }
                    //Logger::info("正在查询队列 ... \n");
                    //消费队列中的消息
                    $download_list = $redis->xReadGroup($group, $consumer, [$stream => '>'], $limit, $block);
                    if (!empty($download_list)) {
                        $download_list = $download_list[$stream];
                        Logger::info(sprintf(
                            "==========导出数量: %d, 任务信息:%s \n",
                            count($download_list),
                            json_encode($download_list)
                        ));

                        $download_model = new AdminDownloadRecordModel();
                        $download_model->ensureConnection($download_model::MODE_CONNECTION_ALL);
                        foreach ($download_list as $messageId => $downloadInfo) {
                            if (empty($downloadInfo['type']) || $downloadInfo['type'] != DownloadLogic::QUEUE_MESSAGE_TYPE_DOWNLOAD) {
                                continue;
                            }
                            if (empty($downloadInfo['id'])) {
                                continue;
                            }

                            $download_id = $downloadInfo['id'];
                            Logger::info(sprintf(
                                "==========开始导出任务 download_id=%d==============\n",
                                $download_id
                            ));

                            $download_data = $download_model->findFirst($download_id);
                            $download_data->update_time = time();
                            $download_data->status = DownloadLogic::STATUS_EXPORTING;
                            $res = $download_data->save();
                            if ($res === false) {
                                Logger::error(sprintf(
                                    "修改数据 download_id=%d 导出状态（正在生成）失败! \n",
                                    $download_id
                                ));
                            } else {
                                if ($res > 0) {
                                    $worker_id = mt_rand(0, $server->setting['worker_num'] - 1);
                                    $server->sendMessage(json_encode([
                                        'type' => Constant::DOWNLOAD_TYPE_TASK,
                                        'data' => serialize(['download_id' => $download_id]),
                                    ]), $worker_id);

                                    //redis队列ack 确认消费
                                    $redis->xAck($stream,$group,[$messageId]);

                                    Logger::info(sprintf(
                                        "导出任务投递至(WorkerID=%d) \n",
                                        $worker_id
                                    ));
                                } else {
                                    Logger::info(sprintf(
                                        "数据导出 download_id=%d 已被其他服务处理...) \n",
                                        $download_id
                                    ));
                                }
                            }
                            unset($download_data, $task_id, $worker_id, $res);
                        }
                    }
                    usleep(1000000);
                    unset($download_list);
                }
            } catch (\Exception $e) {
                if ($e instanceof \PDOException) {
                    Logger::info("PDOException: " . print_r($e->getMessage(), true));
                } else {
                    Logger::info(sprintf("PROCESS Exception: %s - %s", $e->getCode(), $e->getMessage()));
                }
                unset($e);
                usleep(1000000);
            }
            Logger::info("PROCESS: 数据导出处理程序结束运行...\n");

        }, true);

    }

    static public function autoFix($server, $di)
    {
        return new swoole_process(function ($process) use ($server, $di) {
            sleep(60); //第一次启动时,延迟60秒执行

            global $argv;

            $process_name = 'swoole ' . $argv[0] . ' [process autofix download]' . ' (' . ENVIRON1 . ')';
            if (function_exists('cli_set_process_title')) {
                @cli_set_process_title($process_name);
            } else if (function_exists('swoole_set_process_name')) {
                swoole_set_process_name($process_name);
            }
            $is_running = true;

            declare(ticks=1);

            if (pcntl_signal(SIGTERM, function () use (&$is_running) {
                    Logger::info("set SIGTERM signale handle success!");
                    $is_running = 0;
                }) === false) {
                Logger::error("set SIGTERM signale handle error!");
            }

            Logger::info('导出状态自动修复程序开始运行' . PHP_EOL);

            /**
             * @var $redis Redis
             */
            $redis = $di->get('redis');

            //测试环境,预发布环境,根据环境变量1执行相应任务
            $other_con = '';
            $environ1 = DownloadLogic::getEnviron1();
            if (!empty($environ1)) {
                $other_con .= " AND environ1 = '{$environ1}' ";
            }

            while (true) {
                if (!$is_running) {
                    break;
                }
                try {
                    $fix_time = time();
                    //获取需修复的记录
                    $fix_list = AdminDownloadRecordModel::find([
                        'columns' => '*',
                        'conditions' => "(status=:status_wait: OR status=:status_exporting:) 
                                        AND create_time<=:limit_time: "
                                        . $other_con,
                        'bind' => [
                            'status_wait' => DownloadLogic::STATUS_WAIT_EXPORT,
                            'status_exporting' => DownloadLogic::STATUS_EXPORTING,
                            'limit_time' => time() - DownloadLogic::AUTO_FIX_SECONDS
                        ]
                    ]);

                    $totalCount = $fix_list->count();
                    //开始修复
                    if ($totalCount > 0) {
                        $reloadSuc = [];
                        $reloadErr = [];
                        $timeOut   = [];
                        foreach ($fix_list as $row) {

                            switch($row->status){
                                case DownloadLogic::STATUS_WAIT_EXPORT:
                                    //更新表中的修复时间
                                    $row->autofix_time = time();
                                    $row->save();

                                    //待导出的任务
                                    //重新将任务添加至队列中
                                    $redisRes = $redis->xAdd(
                                        DownloadLogic::QUEUE_STREAM,
                                        [
                                            'id' => $row->id,
                                            'type' => DownloadLogic::QUEUE_MESSAGE_TYPE_DOWNLOAD,
                                        ],
                                        '*'
                                    );
                                    if($redisRes == false){
                                        //写入队列失败
                                        $reloadErr[] = $row->id;
                                    }else{
                                        //写入队列成功
                                        $reloadSuc[] = $row->id;
                                    }

                                    break;
                                case DownloadLogic::STATUS_EXPORTING:
                                    //改变任务状态为导出失败
                                    $row->status = DownloadLogic::STATUS_EXPORT_FAIL;
                                    $row->autofix_time = time();
                                    $row->save();

                                    //导出中的任务(超时)
                                    $timeOut[] = $row->id;
                                    break;
                            }
                        }

                        //未知原因导出超时-发送告警信息
                        \Notice::send(
                            \Constant::DOWNLOAD_TYPE_NOTICE,
                            1,
                            '数据导出超时(' . DownloadLogic::AUTO_FIX_SECONDS . '秒)',
                            0,
                            [
                                ['type' => 1, 'name' => '修复时间(autofix_time)', 'val' => $fix_time],
                                ['type' => 1, 'name' => '需修复任务总数量', 'val' => $totalCount],
                                ['type' => 3, 'name' => '超时任务ID组', 'val' => $timeOut ? implode(',', $timeOut) : ' '],
                                ['type' => 1, 'name' => '重新导出入队成功', 'val' => $reloadSuc ? implode(',', $reloadSuc) : ' '],
                                ['type' => 3, 'name' => '重新导出入队失败', 'val' => $reloadErr ? implode(',', $reloadErr) : ' '],
                                ['type' => 1, 'name' => 'ENVIRON', 'val' => $environ1],
                            ]
                        );
                    }

                    sleep(60);
                } catch (Exception $e) {
                    Logger::error(sprintf("导出状态修复异常: %s[%s] \n", $e->getMessage(), $e->getCode()));
                    unset($e, $send);
                    sleep(1);
                }
            }
            Logger::info('PROCESS: 导出状态自动修复程序结束' . PHP_EOL);
        });
    }
}
