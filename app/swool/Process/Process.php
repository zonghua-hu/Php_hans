<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/15
 * Time: 14:34
 */
$process = new swoole_process('callbackFunction', false);


$pid = $process->start();

echo "子进程id：".$pid;

function callbackFunction(swoole_process $worker)
{
    sleep(2);
}

swoole_process::wait();