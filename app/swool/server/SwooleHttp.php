<?php


namespace swool;

use Swoole\Http\Server;

class SwooleHttp
{
    private static $install = null;
    private $ipOne = '0.0.0.0';
    private $ipTwo = '127.0.0.1';
    private $port = 9501;

    public function __construct($params)
    {
        $server = new Server($this->ipTwo, $this->port);
        $server->on('request', [$this, 'onRequest']);
        $server->start();
    }

    public function onRequest($request, $response)
    {
        var_dump($request->server);
        if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
            $response->end();
            return;
        }
        $response->header("Content-Type", "text/html; charset=utf-8");
        $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
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
