<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/11
 * Time: 11:39
 */

interface  GamePlayer
{
    public function login($user,$pwd);

    public function killBoss();

    public function upgrade();
}