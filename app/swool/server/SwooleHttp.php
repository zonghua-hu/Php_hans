<?php


namespace swool;

use Swoole\Http\Server;

class SwooleHttp
{
    private static $install = null;

    public function __construct($params)
    {
        $server = new Server($params['ip'], $params['port']);

        $server->on('request', function ($request, $response) {
            var_dump($request->server);
            $response->header("Content-Type", "text/html; charset=utf-8");
            $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
        });

        $server->start();
    }

    /**
     * @Notes:单利启动http服务
     * @return SwooleHttp|null
     * @User: Hans
     * @Date: 2020/8/6
     * @Time: 11:58 上午
     */
    public static function run($params)
    {
        if (!self::$install instanceof self) {
            self::$install = new self($params);
        }
        return self::$install;
    }
}
