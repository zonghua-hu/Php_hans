<?php

class Task
{
    protected $taskId;
    protected $coroutine;
    protected $sendValue;
    protected $beforeFirstYield = true;

    public function __construct($taskId, Generator $coroutine)
    {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }

    public function getTaskId()
    {
        return new SystemCall(function (Task $task, Scheduler $scheduler) {
            $task->setSendValue($task->getTaskId());
            $scheduler->schedule($task);
        });
    }

    public function setSendValue($sendValue)
    {
        $this->sendValue = $sendValue;
    }

    public function run()
    {
        if ($this->beforeFirstYield) {
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        } else {
            $resVal = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $resVal;
        }
    }

    public function isFinished()
    {
        return !$this->coroutine->valid();
    }

    public function task($max)
    {
        $tid = (yield $this->getTaskId());
        for ($i=1; $i<=$max; $i++) {
            echo "this is task tid:".$tid."loop:".$i;
            yield;
        }
    }
}
