<?php

use Swoole\Coroutine as co;
use Swoole\Coroutine\MySQL;
use Swoole\Process\Manager;
use Swoole\Process\Pool;

//php WeChatCoroutine.php --ENVIRON=develop --DATE=2020-11-16

// 参数处理
$options = getopt('n:m:', ['ENVIRON:', 'DATE:']);
if (isset($options['ENVIRON'])) {
    $environ = $options['ENVIRON'];
} else {
    $environ = 'develop';
}

if (!isset($options['DATE'])) {
    die("请指定运行日期 --DATE！\n");
}
$startDate = $options['DATE'];

co::set(['hook_flags' => SWOOLE_HOOK_ALL | SWOOLE_HOOK_CURL]);

try {

    go(function () use ($startDate, $environ) {
        //测试库
        $testDbCon = [
            'host'        => 'dev-mysql.shylwlkj.com',
            'user'        => 'root',
            'password'    => 'cdb-97e8pp7v2019',
            'port'        => '60853',
            'database'    => 'business',
        ];
        //预发布库
        $preDbCon = [
            'host'        => '10.1.3.2',
            'user'        => 'preview',
            'password'    => 'FMNxXvjaM0sINcQ!',
            'port'        => '3306',
            'database'    => 'business',
        ];
        $url = "http://106.52.206.103:20010/component/get_access_token_by_app_id";
        switch ($environ) {
            case 'production':
                $dbConfig = [];
                $url = '';
                break;
            case 'preview_new':
                $dbConfig = $preDbCon;
                break;
            case 'test':
            case 'develop':
                $dbConfig = $testDbCon;
                break;
        }
        $mysql = new Swoole\Coroutine\MySQL();
        $db = $mysql->connect($dbConfig);
        $apiResSql = "select id,url,app_type from yy_wechat_attention_config where delete_time=0";
        $apiRes = $mysql->query($apiResSql);
        if (empty($apiRes) || !$apiRes) {
            echo "暂无需要跑的数据～";
        }

        /**
         * @Notes:CURL请求
         * @param $url
         * @param $data
         * @param bool $type
         * @return bool|string|null
         * @User: Hans
         * @Date: 2020/12/10
         * @Time: 下午3:04
         */
        function send($url, $data, $type = false)
        {
            $data = $type ? http_build_query($data):$data;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // post传输数据
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            if ($error_code = curl_errno($ch)) {
                $error_msg = curl_strerror($error_code);
                return $error_msg;
            }
            curl_close($ch);
            return $result;
        }

        //token管道，sql数据管道，appid管道，插入sql语句管道
        $accessTokenChannel = $insertChannel = $appIdChannel = $sqlChannel = new co\Channel();
        //获取appId信息

        go(function () use ($apiRes, $appIdChannel, $mysql) {
            $data['apiNum'] = count($apiRes);
            foreach ($apiRes as $item) {
                switch ($item['app_type']) {
                    case 1:
                        //公众号
                        $appIdSql = "select appid from yy_merchant_open_info_copy1
where is_delete=0 and appid_type=1";
                        $appIdRes = $mysql->query($appIdSql);
                        if (empty($appIdRes) || !$appIdRes) {
                            throw new \RuntimeException("暂无需要跑的appId数据～", 403);
                        }
                        foreach ($appIdRes as $key => $value) {
                            if (!empty($value['appid']) && strlen($value['appid']) == 18) {
                                $appIdChannel->push($value['appid']);
                            }
                        }
                        break;
                    case 2:
                        //todo 小程序
                        break;
                }
            }
        });
        //获取accessToken
        go(function () use ($appIdChannel, $accessTokenChannel, $url) {
            while ($appId = $appIdChannel->pop(5)) {
                $tokenRes = send($url, ['appid'=>$appId], true);
                $tokenRes = json_decode($tokenRes, true);
                if (!isset($tokenRes['status']) || $tokenRes['status'] != 200) {
                    throw new \RuntimeException("token获取失败", 404);
                }
                $accessTokenChannel->push([
                                              'appId' => $appId,
                                              'token' => $tokenRes['data']
                                          ]);
            }
        });
        //获取微信数据
        go(function () use ($accessTokenChannel, $apiRes, $startDate, $insertChannel, $mysql) {
            $record = [
                'app_id' => '',
                'app_type' => 1,
                'config_id' => 1,
                'request_params' => json_encode([]),
                'data_source' => '',
                'batch_date' => $startDate,
                'status' => 1,
                'create_time' => time(),
                'update_time' => time()
            ];
            $succeedNum = 0;
            foreach ($apiRes as $item) {
                switch ($item['app_type']) {
                    case 1:
                        $params = [
                            'begin_date' => $startDate,
                            'end_date' => $startDate
                        ];
                        $record['app_type'] = 1;
                        $record['config_id'] = $item['id'];
                        while ($token = $accessTokenChannel->pop(10)) {
                            //查看是否为修复数据
                            $tempSql = "select id from yy_wechat_attention where delete_time=0 and 
config_id={$item['id']} and app_id=\'{$token['appId']}\' 
and app_type=1 and batch_date=\'{$startDate}\'";
                            $fixData = $mysql->query($tempSql);
                            if ($fixData) {
                                $updateId = current($fixData)['id'];
                            } else {
                                $updateId = 0;
                            }
                            $updateFlag = true;

                            $record['app_id'] = $token['appId'];
                            $res = send($item['url'].$token['token'], json_encode($params));
                            if (!$res) {
                                $params = $record['app_id']."::微信接口失败";
                                $record['status'] = 1;
                            } else {
                                $res = json_decode($res, true);
                                if (isset($res['errcode'])) {
                                    $record['status'] = 1;
                                } else {
                                    $succeedNum ++;
                                    $updateFlag = true;
                                    $record['status'] = 2;
                                }
                                $params = json_encode($res);
                            }
                            $record['data_source'] = $params;
                            if ($updateId) {
                                if ($updateFlag) {
                                    $record['id'] = $updateId;
                                    $record['status'] = 2;
                                    $record['data_source'] = $params;
                                    $record['create_time'] = time();
                                    $record['update_time'] = time();
                                }
                            }
                            $insertChannel->push($record);
                        }
                        break;
                    case 2:
                        break;
                }
            }
        });
        //组装sql
        go(function () use ($insertChannel, $sqlChannel) {
            $insertSql = "INSERT INTO yy_wechat_attention(app_id,app_type,config_id,request_params,
data_source,batch_date,status,create_time,update_time)VALUES";
            while ($record = $insertChannel->pop(10)) {
                if (isset($record['id'])) {
                    $tempSql = "UPDATE yy_wechat_attention SET data_source={$record['data_source']},
 status={$record['status']},create_time={$record['create_time']},
 update_time={$record['update_time']} where id = {$record['id']};";
                } else {
                    $tempSql = $insertSql .= "('".join('\',\'', $record)."');";
                }
                $sqlChannel->push($tempSql);
            }
        });
        //批量插入
        go(function () use ($mysql, $sqlChannel) {
            $sql = "";
            $i = 0;
            while ($ch = $sqlChannel->pop(1)) {
                $sql .= $ch;
                $insertRes = $mysql->query($sql);
                if (!$insertRes) {
                    throw new \RuntimeException("批量插入数据失败～", 405);
                }
                //$sql = "";
//                $i = 0;
//                if ($i >= 1) {
//                    $insertRes = $mysql->query($sql);
//                    if (!$insertRes) {
//                        throw new \RuntimeException("批量插入数据失败～", 405);
//                    }
//                    $sql = "";
//                    $i = 0;
//                } else {
//                    $sql .= $ch;
//                    $i++;
//                }
            }
        });
        echo "批跑数据完成~";
    });
} catch (\Throwable $e) {
    echo $e;
}
