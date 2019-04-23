<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/23
 * Time: 10:20
 */

class Context
{
    private $istragy;


    public function __construct($obj)
    {
        if ($obj instanceof BackDoor || $obj instanceof BlackEnemy || $obj instanceof GreenLight) {
            $this->istragy = $obj;
        } else {
            $this->istragy = new BackDoor();
        }
    }

    public function operate()
    {
        $this->istragy->operation();
    }

}