<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/6
 * Time: 11:41
 */

class OpeningState extends LiftState
{
    public function close()
    {
        // TODO: Implement close() method.
        parent::setContext();
        echo "电梯关闭";
    }

    public function open()
    {
        // TODO: Implement open() method.
        echo "电梯打开";
    }
    public function run()
    {
        // TODO: Implement run() method.

    }

    public function stop()
    {
        // TODO: Implement stop() method.

    }


}