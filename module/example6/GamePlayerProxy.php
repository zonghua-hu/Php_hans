<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/11
 * Time: 11:41
 */

class GamePlayerProxy implements GamePlayer
{
    private $gamer = null;

    public function __construct($gam)
    {
        if (!$gam instanceof GamePlayers) {
            $gam = new GamePlayers();
        }
        return $this->gamer = $gam;
    }
    /**
     * 代练登陆
     * @param $user
     * @param $pwd
     * @return string
     * @author Hans
     * @date 2019/4/11
     * @time 11:59
     */
    public function login($user,$pwd)
    {
        return $this->gamer->login($user,$pwd);
    }

    /**
     * 代练杀怪
     * @return string
     * @author Hans
     * @date 2019/4/11
     * @time 11:59
     */
    public function killBoss()
    {
        return $this->gamer->killBoss();
    }

    /**
     * 代练升级
     * @return string
     * @author Hans
     * @date 2019/4/11
     * @time 11:59
     */
    public function upgrade()
    {
        return $this->gamer->killBoss();
    }

}