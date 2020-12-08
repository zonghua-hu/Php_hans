<?php

class client
{
//    public function taskOne()
//    {
//        for ($i=1; $i<=10; $i++) {
//            echo "this is task 1".$i;
//            yield;
//        }
//    }
//
//    public function taskTwo()
//    {
//        for ($i = 1; $i<=10; $i++) {
//            echo "this is task 2".$i;
//            yield;
//        }
//    }
//
    public function run()
    {
        $schedule = new Scheduler();
        $schedule->newTask(task(10));
        $schedule->newTask($this->taskTwo());
        $schedule->run();
    }



}
