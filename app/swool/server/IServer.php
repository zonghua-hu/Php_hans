<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/1
 * Time: 16:47
 */
namespace Swoft\server;

use Swoole\Server;

interface IServer
{
    const TYPE_HTTP = 'http';
    const TYPE_RPC = 'rpc';
    const TYPE_TCP = 'tcp';
    const TYPE_WS = 'ws';

    public function start();
    public function stop():bool;
    public function reload($onlyTask = false);
    public function isRunning():bool;
    public function getServer():Server;
    public function getTcpSetting():array;
    public function getHttpSetting():array;
    public function getServerSetting():array;
    public function setDaemonize();
    public function getServerType():string;
}