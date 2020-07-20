<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 17:06
 */
namespace example1;

class Minister
{
    //use peoples;

    public function __construct()
    {
        $this->name = 'Bob';
        $this->age = 40;
        $this->work = 'teacher';
        $this->run();
    }

    public function getEmperorSays()
    {
        $emperor = Emperor::getEmperor();
        $emperor->emperorSay();
    }

    public function run()
    {
        $res =  'The'.$this->age.'years people'.$this->name.'running to school,because he is a '.$this->work;
        print_r($res);
    }
}
