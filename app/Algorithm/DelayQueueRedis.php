<?php

namespace app\Algorithm;

class DelayQueueRedis
{
    protected $prefix = 'delay_queue';
    protected $redis = null;
    protected $key = '';

    public function __construct($queue, $config = [])
    {
        $this->key = $this->prefix . $queue;
        $this->redis = new \Redis();
        $this->redis->connect($config['host'], $config['port'], $config['timeout']);
        $this->redis->auth($config['auth']);
    }

    public function delTask($value)
    {
        return $this->redis->zRem($this->key, $value);
    }

    public function getTask()
    {
        return $this->redis->zRangeByScore($this->key, 0, time(), ['limit' => [0,1]]);
    }

    public function addTask($name, $time, $data)
    {
        return $this->redis->zAdd(
            $this->key,
            $time,
            json_encode([
                'task_name' => $name,
                'task_time' => $time,
                'task_params' => $data
            ], JSON_UNESCAPED_UNICODE)
        );
    }

    public function run()
    {
        $task = $this->getTask();
        if (empty($task)) {
            return false;
        }
        $task = $task[0];
        if ($this->delTask($task)) {
            $task = json_decode($task, true);
            echo "任务：" . $task['task_name'] . "运行时间:" . PHP_EOL;
            return true;
        }
        return false;
    }


}