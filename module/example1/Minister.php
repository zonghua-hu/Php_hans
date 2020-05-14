<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 17:06
 */


class Minister
{
    use peoples;

    public function __construct()
    {
        $this->name = 'Bob';
        $this->age = 40;
        $this->work = 'teacher';
    }

    public function getEmperorSays()
    {
        $emperor = Emperor::getEmperor();
        $emperor->emperorSay();
    }

    public function run()
    {
        return 'The'.$this->age.'years people'.$this->name.'running to school,because he is a '.$this->work;
    }
}