<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/6
 * Time: 11:37
 */

abstract class Contexts
{
    public $con;
    public $open_state;
    public $close_state;
    public $run_state;
    public $stop_state;
    private $lift_state;

    public function __construct()
    {
        $this->open_state = new OpeningState();
        $this->close_state = new CloseingState();
        $this->run_state = new RuningState();
        $this->stop_state = new StopingState();
    }

    public function getLiftState(LiftState $liftState)
    {
        $this->lift_state = $liftState;
        $this->lift_state->setContext($this);
    }

    public function open()
    {
        $this->lift_state->open();
    }

    public function close()
    {
        $this->lift_state->close();
    }

    public function run()
    {
        $this->lift_state->run();
    }

    public function stop()
    {
        $this->lift_state->stop();
    }

}