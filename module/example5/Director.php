<?php
/**
 * 导演类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 10:40
 */

class Director
{
    private $ben;
    private $bwm;

    public function __construct()
    {
        $this->ben = new BenzBuild();
        $this->bwm = new BwmBuild();
    }

    /**
     * 生产奔驰车
     * @return BenzModule
     * @author Hans
     * @date 2019/4/10
     * @time 10:56
     */
    public function getBenzModule()
    {
        $ben_list = ['start','alarm','engineBoom','stop'];
        $this->ben->setList($ben_list);
        return $this->ben->getCarModule();
    }

    /**
     * 生产宝马车
     * @return BwmModule
     * @author Hans
     * @date 2019/4/10
     * @time 10:56
     */
    public function getBwmModule()
    {
        $bwm_list = ['start','engineBoom','stop'];
        $this->bwm->setList($bwm_list);
        return $this->bwm->getCarModule();
    }

}