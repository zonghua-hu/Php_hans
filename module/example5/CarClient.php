<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 10:16
 */

class CarClient
{
    private $director;

    public function __construct()
    {
        $this->director = new Director();
    }

    /**
     * 改造前的建造者模式
     * @author Hans
     * @date 2019/4/10
     * @time 10:50
     */
    public function main()
    {
        /**
         * 奔驰车建造过程
         */
        $benz_list = ['start','alarm','engineBoom','stop'];
        $benz_build = new BenzBuild();
        $benz_build->setList($benz_list);
        $benz_build->getCarModule();
        $benz = $benz_build->getCarModule();
        $benz->run();
        /**
         * 宝马车建造过程
         */
        $bwm_list = ['start','engineBoom','alarm','stop'];
        $bwm_build = new BwmBuild();
        $bwm_build->setList($bwm_list);
        $bwm = $bwm_build->getCarModule();
        $bwm->run();
    }

    /**
     * 改进后的建造者模式
     * @author Hans
     * @date 2019/4/10
     * @time 10:49
     */
    public function mainMaster()
    {
        /**
         * 生产奔驰车过程
         */
        $this->director->getBenzModule()->run();
        /**
         * 生产宝马车过程
         */
        $this->director->getBwmModule()->run();

    }

}