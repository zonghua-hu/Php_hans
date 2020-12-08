<?php

namespace Kafka;

use Pheanstalk\Exception;

class testKafka
{
    private $config = [
        'queue' => [
            'class'   => KafkaQueue::class,
            'config'  => [
                'broker_list' => '10.1.4.15:9092',
                'customer_realtime' => [
                    'partition_num' => 6
                ],
                //小程序notify方法所对应的队列配置
                'micro_notify_queue' => [
                    'acks' => -1,
                    'consumer_group' => 'micro_notify_group',
                    'offset' => 'latest',
                    'partition_num' => 3,
                ],
                //POS机notify方法所对应的队列配置
                'pos_notify_queue' => [
                    'acks' => -1,
                    'consumer_group' => 'pos_notify_group',
                    'offset' => 'latest',
                    'partition_num' => 3,
                ],
                //充值送券队列配置
                'recharge_send_coupon_queue' => [
                    'acks' => -1,
                    'consumer_group' => 'recharge_consumer_group',
                    'offset' => 'latest',
                    'partition_num' => 3,
                ],
            ]
        ]
    ];


    public function test()
    {
        $name = "kafka-test";
        $params = [1,2,3,4,5];
        $result = KafkaClient::publish($name, $params, $this->config);
        if ($result == false) {
            throw new Exception('投递任务失败~');
        }
        return true;
    }

}
