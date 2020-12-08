<?php


namespace Kafka;


use Swoole\Exception;

class KafkaClient
{
    protected static $config;
    protected static $queue;

    public static function setConfig(array $config)
    {
        self::$config = $config;
    }

    public static function getConfig(array $config)
    {
        if (empty(self::$config)) {
            self::$config = $config;
//            self::$config = \Phalcon\Di::getDefault()->getConfig()['queue'];
            if (is_object(self::$config)) {
                self::$config = self::$config->toArray();
            }
        }
        return self::$config;
    }

    /**
     * @Notes:队列初始化
     * @param $queue_name
     * @User: Hans
     * @Date: 2020/12/8
     * @Time: 下午3:02
     * @throws \Exception
     */
    public static function init($queue_name, $config = [])
    {
        $config = self::getConfig($config);
        if (empty($config)) {
            throw new \Exception('获取配置信息失败！');
        }

        $class = $config['class'];
        if (!class_exists($class)) {
            throw new \Exception($class.'加载失败！');
        }

        if (empty($config['config']['broker_list'])) {
            throw new \Exception('消息队列的broker集群地址不能为空');
        }

        //当前待实例化队里的各项私有配置
        $queue_private_config = $config['config'][$queue_name] ?? [];
        //$conf即为当前待实例化队列的各项私有配置加上了集群地址
        $queue_private_config['broker_list'] = $config['config']['broker_list'];

        self::$queue = new $class($queue_private_config);
    }

    /**
     * @Notes:加入队列
     * @param $queueName
     * @param $message
     * @return bool
     * @User: Hans
     * @Date: 2020/12/8
     * @Time: 下午3:04
     * @throws Exception
     */
    public static function publish($queueName, $message, $config = [])
    {
        try {
            self::init($queueName, $config);
            self::$queue->publish($queueName, $message);
            self::freeResult();
            return true;
        } catch (\Throwable $exception) {
            throw new \Pheanstalk\Exception($exception->getCode(), $exception->getMessage());
        }
    }

    /**
     * @Notes:消费队列消息
     * @param $queueName
     * @param callable $callback
     * @return bool
     * @User: Hans
     * @Date: 2020/12/8
     * @Time: 下午3:07
     * @throws \Pheanstalk\Exception
     */
    public static function consume($queueName, callable $callback)
    {
        try {
            self::init($queueName);
            self::$queue->consume($queueName, $callback);
            self::freeResult();
            return true;
        } catch (\Throwable $e) {
            throw new \Pheanstalk\Exception($e->getCode(), $e->getMessage());
        }
    }

    /**
     * @desc:释放静态资源
     * @author: wuxy02
     * @date: 2020-08-20
     * @time: 12:12
     */
    public static function freeResult()
    {
        self::$config = null;
        self::$queue  = null;
    }
    /**
     * @desc:消费队列数据一次
     * @param string $queue_name 队列名称
     * @param callable $callback 回调函数
     * @author: Roy
     * @return mixed
     */
    public static function consumeOnce($queue_name, callable $callback)
    {
        try {
            self::init($queue_name);
            self::$queue->consumeOnce($queue_name, $callback);
            self::freeResult();
            return true;
        } catch (\Throwable $e) {
            throw new \Pheanstalk\Exception($e->getCode(), $e->getMessage());
        }
    }
}
