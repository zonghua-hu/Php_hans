<?php

use generator\Scheduler;
use generator\SystemCall;
use generator\Task;

require_once 'Scheduler.php';
require_once 'Task.php';
require_once 'SystemCall.php';

/**
 * 将迭代器放入队列，因为迭代器的缘故，所以在执行队列任务的时候，两个任务交替执行
 */


function getTaskId() {
    return new SystemCall(function(Task $task, Scheduler $scheduler) {
        $task->setSendValue($task->getTaskId());
        $scheduler->schedule($task);
    });
}

function newTask(Generator $coroutine) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($coroutine) {
            $task->setSendValue($scheduler->newTask($coroutine));
            $scheduler->schedule($task);
        }
    );
}

function killTask($tid) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($tid) {
            $task->setSendValue($scheduler->killTask($tid));
            $scheduler->schedule($task);
        }
    );
}

function childTask() {
    $tid = (yield getTaskId());
    while (true) {
        echo "Child task $tid still alive!\n";
        yield;
    }
}
function task() {
    $tid = (yield getTaskId());
    $childTid = (yield newTask(childTask()));
    for ($i = 1; $i <= 6; ++$i) {
        echo "Parent task $tid iteration $i.\n";
        yield;
        if ($i == 3) yield killTask($childTid);
    }
}
function taskOne($max)
{
    for ($i = 0; $i <= $max; ++ $i) {
        $tid = (yield getTaskId());
        echo "this is task $tid iteration $i.\n";
        yield;
    }
}
function taskTwo()
{
    for ($i = 1; $i <=5; ++ $i) {
        echo "this is task 2 iteration $i.\n";
        yield;
    }
}
//----------------eg 1
//$schedule = new \generator\Scheduler();
//$schedule->newTask(taskOne(10));
//$schedule->newTask(taskOne(5));
//$schedule->run();
//------------------eg 2
$scheduler = new Scheduler;
$scheduler->newTask(task());
$scheduler->run();
