<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 19:53
 */

 class UserBehavior
{
    public function getEchoResult()
    {
        $obj_user = new UserAgent();
        $result = $obj_user->getObjectUser();
    }

}