<?php


namespace swool;

use Swoole\WebSocket\Server;

class SwooleWebSocket
{
    private static $install = null;

    public function __construct($params)
    {
        $server = new Server($params['ip'], $params['port']);

        $server->on('open', function (Server $server, $request) {
            echo "server: handshake success with fd{$request->fd}\n";
        });

        $server->on('message', function (Server $server, $frame) {
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            $server->push($frame->fd, "this is server");
        });

        $server->on('close', function ($ser, $fd) {
            echo "client {$fd} closed\n";
        });

        $server->start();
    }

    /**
     * @Notes:单利启动socket
     * @return SwooleWebSocket|null
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
