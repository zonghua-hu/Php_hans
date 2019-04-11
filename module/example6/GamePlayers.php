<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/11
 * Time: 11:43
 */

class GamePlayers implements GamePlayer
{
    private $name;

    public function getGamer($str)
    {
        $this->name = $str;
    }

    public function login($accunt,$password)
    {
        return "登录名为：".$accunt."的用户，名字为：".$this->name."登陆成功~~";
    }

    public function killBoss()
    {
        return $this->name."正在击杀boss~~";
    }

    public function upgrade()
    {
        return $this->name."恭喜升级~~~";
    }

}