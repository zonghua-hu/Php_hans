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
        $this->user = $_GET['user'];
        $this->pwd  = $_GET['pwd'];
    }

    public function main()
    {
        $pro_game = new GamePlayerProxy(new GamePlayers());

        $pro_game->login($this->user,$this->pwd);

        $pro_game->killBoss();

        $pro_game->upgrade();
    }

}