<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/29
 * Time: 16:13
 */


class ClientHans
{
    protected $client;

    public function __construct()
    {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP,SWOOLE_SOCK_ASYNC);
        $this->client->on("connect", function(swoole_client $cli) {
            $cli->send("GET / HTTP/1.1\r\n\r\n");
        });
        $this->client->on("receive", function(swoole_client $cli, $data){
            echo "Receive: $data";
            $cli->send(str_repeat('A', 100)."\n");
            sleep(1);
        });
        $this->client->on("error", function(swoole_client $cli){
            echo "error\n";
        });
        $this->client->on("close", function(swoole_client $cli){
            echo "Connection close\n";
        });
        $this->client->connect('127.0.0.1', 9501);
    }

}