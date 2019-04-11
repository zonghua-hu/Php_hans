<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/11
 * Time: 11:50
 */

class GameCilent
{
    private $user;
    private $pwd;

    public function __construct()
    {
        self::setInfo();
    }

    private function setInfo()
    {
        $this->user = isset($_GET['user'])??'admin';
        $this->pwd  = isset($_GET['pwd'])??'admin0012';
    }

    public function main()
    {
        $pro_game = new GamePlayerProxy(new GamePlayers());

        $pro_game->login($this->user,$this->pwd);

        $pro_game->killBoss();

        $pro_game->upgrade();
    }

}