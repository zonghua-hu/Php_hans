<?php


namespace Kafka;

class KafkaQueue implements Queue
{
    /**
     * 集群地址
     * @var string
     */
    protected $broker_list;
    /**
     * 生产者模式标志
     * Kafka producer 的 ack 有 3 种机制，分别说明如下:
     * -1 或 all：Broker 在 leader 收到数据并同步给所有 ISR 中的 follower 后.
     *  才应答给 Producer 继续发送下一条（批）消息。 这种配置提供了最高的数据可靠性，只要有一个已同步的副本存活就不会有消息丢失。
     * 0：生产者不等待来自 broker 同步完成的确认，继续发送下一条（批）消息。这种配置生产性能最高，但数据可靠性最低.
     * 1：生产者在 leader 已成功收到的数据并得到确认后再发送下一条（批）消息。这种配置是在生产吞吐和数据可靠性之间的权衡.
     * 默认值为 -1
     * @var int
     */
    protected $acks = -1;
    /**
     * 消费者组
     * @var string
     */
    protected $consumer_group = "myConsumeGroup";

    /**
     * 1、当 Broker 端没有 offset（如第一次消费或 offset 超过7天过期）时如何初始化 offset。
     * 2、当收到 OFFSET_OUT_OF_RANGE 错误时，如何重置 Offset。
     * earliest：表示自动重置到 partition 的最小 offset。
     * latest：默认为 latest，表示自动重置到 partition 的最大 offset。
     * none：不自动进行 offset 重置，抛出 OffsetOutOfRangeException 异常。
     * 默认值：smallest
     * @var string
     */
    protected $auto_offset_reset = 'smallest';

    /**
     * 队列分区的个数
     * @var int
     * 默认值 2
     */
    protected $partition_num = 2;

    public function __construct(array $config = [])
    {
        foreach ($config as $name => $value) {
            $this->$name = $value;
        }
        return $this;
    }

    /**
     * @Notes:创建生产者
     * @return \RdKafka\Producer
     * @User: Hans
     * @Date: 2020/12/8
     * @Time: 下午2:38
     */
    public function createProducer()
    {
        $conf = new \RdKafka\Conf();
        $conf->set('metadata.broker.list', $this->broker_list);
        $conf->set('request.required.acks', $this->acks);

        return new \RdKafka\Producer($conf);
    }

    /**
     * @Notes:获取数据落选分区
     * @param $queueName
     * @param $message
     * @return int
     * @User: Hans
     * @Date: 2020/12/8
     * @Time: 下午2:41
     */
    public function getPartition($queueName, $message)
    {
        $partition = RD_KAFKA_PARTITION_UA;
        if (!empty($queueName)) {
            $data = json_decode($message, true);
            $messageId = 0;
            if (!empty($data['message_id'])) {
                $messageId = $data['message_id'];
            } else {
                if (!empty($data['user_id'])) {
                    $messageId = $data['user_id'];
                }
            }

            if ($messageId > 0) {
                $partition = $messageId % $this->partition_num;
            }
        }

        return $partition;
    }

    /**
     * @Notes:入队列
     * @param $queueName
     * @param $message
     * @return mixed|void
     * @User: Hans
     * @Date: 2020/12/8
     * @Time: 下午2:42
     */
    public function publish($queueName, $message)
    {
        $producer = $this->createProducer();
        $topic = $producer->newTopic($queueName);

        $partition = $this->getPartition($queueName, $message);
        $topic->produce($partition, 0, $message);

        $producer->poll(0);
        for ($flushRetries = 0; $flushRetries < 10; $flushRetries++) {
            $result = $producer->flush(10000);
            if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
                break;
            }
        }

        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            throw new \Exception("数据可能发生了丢失");
        }
    }

    /**
     * @Notes:创建消费者
     * @return \RdKafka\KafkaConsumer
     * @User: Hans
     * @Date: 2020/12/8
     * @Time: 下午2:50
     */
    public function createConsume()
    {
        $conf = new \RdKafka\Conf();
        $conf->setRebalanceCb(function (\RdKafka\KafkaConsumer $kafka, $err, array $partitions = null) {
            switch ($err) {
                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                    $kafka->assign($partitions);
                    break;
                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                    $kafka->assign(null);
                    break;
                default:
                    throw new \Exception($err);
            }
        });
        $conf->set('group.id', $this->consumer_group);
        $conf->set('metadata.broker.list', $this->broker_list);
        $conf->set('auto.offset.reset', $this->auto_offset_reset);

        return new \RdKafka\KafkaConsumer($conf);
    }

    /**
     * @Notes:消费队列数据
     * @param $queueName
     * @param callable $callback
     * @return mixed|void
     * @User: Hans
     * @Date: 2020/12/8
     * @Time: 下午2:52
     */
    public function consume($queueName, callable $callback)
    {
        $consumer = $this->getConsumer();
        $consumer->subscribe([$queueName]);
        while (true) {
            $message = $consumer->consume(120*1000);
            if (null === $message || $message->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
                continue;
            } elseif ($message->err) {
                $queueName = null;
                $consumer  = null;
                $callback  = null;
                $message   = null;
                break;
            } else {
                call_user_func($callback, $message->payload);
            }
        }
    }

    /**
     * @Notes:一次性只消费一条数据
     * @param $queueName
     * @param callable $callback
     * @return mixed|void
     * @User: Hans
     * @Date: 2020/12/8
     * @Time: 下午2:54
     */
    public function consumeOnce($queueName, callable $callback)
    {
        $consumer = $this->getConsumer();
        $consumer->subscribe([$queueName]);
        $message = $consumer->consume(120 * 1000);
        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                call_user_func($callback, $message->payload);
                break;
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                break;
            case RD_KAFKA_RESP_ERR__TIMED_OUT:
                break;
            default:
                break;
        }
    }
}
